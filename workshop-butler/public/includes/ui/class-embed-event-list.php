<?php
/**
 * The file that defines Embed_Event_List class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( __FILE__ ) . '../class-wsb-page.php';

/**
 * Represents a list of events in a sidebar, either on an event page or a trainer profile
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Embed_Event_List extends WSB_Page {

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
	 * @param $method string Workshop Butler API method
	 * @param $query  array  API parameters
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render( $method, $query ) {
		$response = $this->requests->get( $method, $query );
		return $this->render_list( $response );
	}

	/**
	 * Renders the list of trainers
	 *
	 * @param $response WSB_Response
	 *
	 * @since  2.0.0
	 * @return string
	 */
	private function render_list( $response ) {
		if ( $response->is_error() ) {
			return $this->format_error( $response->error );
		}

		$events = [];
		foreach ( $response->body as $json_event ) {
			$event = new Event(
				$json_event,
				$this->settings->get_event_page_url(),
				$this->settings->get_trainer_page_url(),
				$this->settings->get_registration_page_url()
			);
			array_push( $events, $event );
		}
		$sliced        = array_slice( $events, 0, 5 );
		$template_data = array( 'events' => $sliced );
		$template      = $this->get_template( 'sidebar', null );
		return $this->compile_string( $template, $template_data );
	}
}
