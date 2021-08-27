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
	 * Number of items
	 *
	 * @var int $length
	 * @since 2.7.0
	 */
	private $length;

	/**
	 * ID of event where sidebar is rendered. Can be null if the page is a trainer's profile.
	 *
	 * @var string|null $event_id
	 * @since 2.12.0
	 */
	private $event_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param int         $length Number of items.
	 * @param string|null $event_id ID of event (if any exists).
	 *
	 * @since    2.0.0
	 */
	public function __construct( $length, $event_id ) {
		parent::__construct();
		$this->load_dependencies();
		$this->load_templates();
		$this->requests = new WSB_Requests();
		$this->length   = $length;
		$this->event_id = $event_id;
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
		require_once plugin_dir_path( __FILE__ ) . '../models/class-event-list.php';
		require_once plugin_dir_path( __FILE__ ) . '../models/class-event.php';
	}

	/**
	 * Loads templates used later in the other templates
	 *
	 * @since 2.12.0
	 */
	private function load_templates() {
		$tag = $this->get_template( 'schedule/tag', null );
		$this->twig->loader->setTemplate( 'tag.twig', $tag );
	}

	/**
	 * Retrieves the page data and renders it
	 *
	 * @param string $method Workshop Butler API method.
	 * @param array  $query API parameters.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public function render( $method, $query ) {
		$query['per_page'] = $this->length + 1; // to handle the case when current event is returned in the list.
		$response          = $this->requests->get( $method, $query );

		return $this->render_list( $response );
	}

	/**
	 * Renders the list of events in the sidebar
	 *
	 * @param WSB_Response $response Response.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	private function render_list( $response ) {
		if ( $response->is_error() ) {
			return $this->format_error( $response->error );
		}

		$events             = Event_List::from_json( $response->body->data, $this->settings, false );
		$events             = array_filter(
			$events,
			function ( $event ) {
				return $event->hashed_id !== $this->event_id;
			}
		);
		$sliced             = array_slice( $events, 0, $this->length );
		$tags               = 'free';
		$highlight_featured = false;
		if ( $this->settings->is_highlight_featured() ) {
			$highlight_featured = true;
			$tags               = 'all';
		}
		$template_data = array(
			'events'             => $sliced,
			'highlight_featured' => $highlight_featured,
			'tags'               => $tags,
		);
		$template      = $this->get_template( 'sidebar', null );

		return $this->compile_string( $template, $template_data );
	}
}
