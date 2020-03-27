<?php
/**
 * The file that defines the request wrapper class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

use WP_Error;


define( 'WSB_API_END_POINT', 'https://api.workshopbutler.com/' );
require_once plugin_dir_path( __FILE__ ) . 'class-wsb-response.php';

/**
 * The request wrapper class
 *
 * It's used to make requests to Workshop Butler API in a correct way
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Requests {

	/**
	 * Plugin settings
	 *
	 * @access  protected
	 * @since   2.0.0
	 * @var     WSB_Options $settings Plugin settings
	 */
	protected $settings;

	/**
	 * Initialises a new object
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for all plugin-related options
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '../includes/class-wsb-options.php';

		$this->settings = new WSB_Options();

		/**
		 * The class responsible for plugin-related global variables
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-dictionary.php';
	}

	/**
	 * Makes GET request
	 *
	 * @param string $method API method.
	 * @param array  $query API query parameters.
	 *
	 * @return WSB_Response
	 */
	public function get( $method, $query ) {
		$query['api_key']     = $this->settings->get( WSB_Options::API_KEY );
		$query['t']           = $this->get_plugin_stats();
		$query['api_version'] = WSB_API_VERSION;
		$this->add_stats_parameter( $query );
		$url = WSB_API_END_POINT . $method . '?' . http_build_query( $query );

		return new WSB_Response( wp_remote_get( $url ) );
	}

	/**
	 * Makes POST request
	 *
	 * @param string $method API method.
	 * @param array  $data Post data.
	 *
	 * @return WSB_Response
	 */
	public function post( $method, $data ) {
		$query                = array();
		$query['api_key']     = $this->settings->get( WSB_Options::API_KEY );
		$query['t']           = $this->get_plugin_stats();
		$query['api_version'] = WSB_API_VERSION;

		$this->add_stats_parameter( $query );

		$data_string = json_encode( $data );

		$url = WSB_API_END_POINT . $method . '?' . http_build_query( $query );

		$headers = array(
			'content-type'   => 'application/json',
			'content-length' => strlen( $data_string ),
		);

		$resp = wp_remote_post(
			$url,
			array(
				'method'  => 'POST',
				'headers' => $headers,
				'body'    => $data_string,
			)
		);

		return new WSB_Response( $resp );
	}

	/**
	 * Adds a properly formatted parameter which contains an information about plugin settings
	 *
	 * @param array $query List of query parameters for API request.
	 */
	protected function add_stats_parameter( &$query ) {
		$parameters = array();
		array_push( $parameters, 'w' );
		array_push( $parameters, WSB_INTEGRATION_VERSION );
		array_push( $parameters, $this->settings->get( WSB_Options::THEME, 'alfred' ) );
		$query['t'] = implode( ';', $parameters );
	}

	/**
	 * Retrieves the event from API and adds it to the dictionary
	 *
	 * @param string $id Event ID.
	 *
	 * @return WP_Error|Event
	 */
	public function retrieve_event( $id ) {
		$method   = 'events/';
		$method   = $method . rawurlencode( $id );
		$query    = array( 'expand' => 'trainer.stats' );
		$response = $this->get( $method, $query );
		$dict     = new WSB_Dictionary();
		if ( $response->is_error() ) {
			$error = new WP_Error( $response->http_code, $response->error );
			$dict->set_event( $error );

			return $error;
		} else {
			$event = new Event(
				$response->body->data,
				$this->settings->get_event_page_url(),
				$this->settings->get_trainer_page_url(),
				$this->settings->get_registration_page_url()
			);
			$dict->set_event( $event );

			return $event;
		}
	}

	/**
	 * Retrieves the trainer from API and adds it to the dictionary
	 *
	 * @param string $id Trainer ID.
	 *
	 * @return WP_Error|Trainer
	 */
	public function retrieve_trainer( $id ) {
		$method   = 'facilitators/' . rawurlencode( $id );
		$query    = array();
		$response = $this->get( $method, $query );
		$dict     = new WSB_Dictionary();
		if ( $response->is_error() ) {
			$error = new WP_Error( $response->http_code, $response->error );
			$dict->set_trainer( $error );

			return $error;
		} else {
			$trainer = new Trainer( $response->body->data, $this->settings->get_trainer_page_url() );
			$dict->set_trainer( $trainer );

			return $trainer;
		}
	}

	/**
	 * Returns plugin statistics for request
	 *
	 * @return string
	 * @since 2.7.0
	 */
	protected function get_plugin_stats() {
		return 'w;' . WSB_INTEGRATION_VERSION . ';' . $this->settings->get_theme();
	}

}
