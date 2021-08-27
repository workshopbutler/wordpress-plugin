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
require_once plugin_dir_path( __FILE__ ) . 'utils/log-error.php';

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
		$url        = $this->build_url( $method, $query );
		$request_id = uniqid();
		$args       = array(
			'headers' => array(
				'referer'      => $this->get_referer(),
				'X-Request-Id' => $request_id,
			),
		);
		$resp       = wp_remote_get( $url, $args );
		$this->report_error( $resp, $query, $method, $request_id );

		return new WSB_Response( $resp );
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
		$url = $this->build_url( $method, array() );

		$data_string = json_encode( $data );

		$request_id = uniqid();

		$headers = array(
			'content-type'   => 'application/json',
			'content-length' => strlen( $data_string ),
			'referer'        => $this->get_referer(),
			'X-Request-Id'   => $request_id,
		);

		$resp = wp_remote_post(
			$url,
			array(
				'method'  => 'POST',
				'headers' => $headers,
				'body'    => $data_string,
			)
		);
		$this->report_error( $resp, $data, $method, $request_id );

		return new WSB_Response( $resp );
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
		$parameters = array();
		array_push( $parameters, 'w' );
		array_push( $parameters, WSB_INTEGRATION_VERSION );
		array_push( $parameters, $this->settings->get_theme() );
		array_push( $parameters, WSB_Options::get_template_version() );
		// expose the stage of templates migration
		if ( $this->settings->get( WSB_Options::ALLOW_TEMPLATE_SWITCHING ) ) {
			array_push( $parameters, $this->settings->get( WSB_Options::USE_OLD_TEMPLATES ) ? 'a' : 'b' );
		} else {
			array_push( $parameters, 'c' );
		}
		return implode( ';', $parameters );
	}

	/**
	 * Returns an API url.
	 *
	 * @param string $method POST/GET.
	 * @param array  $query API query parameters.
	 *
	 * @return string
	 * @since 2.9.0
	 */
	protected function build_url( $method, $query ) {
		$query['api_key']     = $this->settings->get( WSB_Options::API_KEY );
		$query['t']           = $this->get_plugin_stats();
		$query['api_version'] = WSB_API_VERSION;

		return WSB_API_END_POINT . $method . '?' . http_build_query( $query );
	}

	/**
	 * Returns current page.
	 *
	 * @return string
	 * @since 2.9.0
	 */
	protected function get_referer() {
		return filter_input( INPUT_SERVER, 'HTTP_HOST' ) . filter_input( INPUT_SERVER, 'REQUEST_URI' );
	}

	/**
	 * If there is a error in response, report it to Workshop Butler
	 *
	 * @param array|WP_Error $resp Response.
	 * @param array          $data Method data.
	 * @param string         $method Method type (POST, GET).
	 * @param string         $request_id ID of the request.
	 */
	protected function report_error( $resp, $data, $method, $request_id ) {
		if ( is_a( $resp, 'WP_Error' ) ) {
			$error_data            = array();
			$data['request_id']    = $request_id;
			$error_data['data']    = $data;
			$error_data['method']  = $method;
			$error_data['code']    = $resp->get_error_code();
			$error_data['message'] = $resp->get_error_message();
			$error_data['errors']  = $resp->get_error_data();
			log_error( 'WSB_Requests', $method, $error_data );
		}
	}
}
