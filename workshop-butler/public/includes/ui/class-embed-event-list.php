<?php
/**
 * The file that defines Embed_Event_List class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . '../class-wsb-page.php';

/**
 * Represents a list of events in a sidebar, either on an event page or a trainer profile
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Embed_Event_List extends WSB_Page {

	/**
	 * Request entity
	 *
	 * @since 2.0.0
	 * @var WSB_Requests $requests
	 */
	private $requests;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		parent::__construct();
		$this->load_dependencies();
		$this->requests = new WSB_Requests();
	}

	/**
	 * Load the required dependencies for this class.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . '../../../includes/class-wsb-options.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-requests.php';
		require_once plugin_dir_path( __FILE__ ) . '../models/class-event.php';
	}

	/**
	 * Retrieves the page data and renders it
	 *
	 * @param string $method Workshop Butler API method.
	 * @param array  $query  API parameters.
	 * @param string $event_id ID of event where the request took place.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render( $method, $query, $event_id ) {
		$response = $this->requests->get( $method, $query );
		return $this->render_list( $response, $event_id );
	}

	/**
	 * Renders the list of trainers
	 *
	 * @param WSB_Response $response Response.
	 * @param string       $event_id ID of event where the request took place.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	private function render_list( $response, $event_id ) {
		if ( $response->is_error() ) {
			return $this->format_error( $response->error );
		}

		$events = array();
		foreach ( $response->body->data as $json_event ) {
			$event = new Event(
				$json_event,
				$this->settings->get_event_page_url(),
				$this->settings->get_trainer_page_url(),
				$this->settings->get_registration_page_url()
			);
			if ( $event->hashed_id !== $event_id ) {
				array_push( $events, $event );
			}
		}
		$sliced        = array_slice( $events, 0, 5 );
		$template_data = array( 'events' => $sliced );
		$template      = $this->get_template( 'sidebar', null );
		return $this->compile_string( $template, $template_data );
	}
}
