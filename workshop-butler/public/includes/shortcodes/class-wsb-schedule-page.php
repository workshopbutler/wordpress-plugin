<?php
/**
 * The file that defines the Schedule class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Schedule page class which handles the rendering and logic for the list of events
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Schedule_Page extends WSB_Page {

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/../../includes/class-wsb-options.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-requests.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'ui/class-event-filters.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event.php';
	}

	/**
	 * Retrieves the page data and renders it
	 *
	 * @param array $attrs
	 * @param null  $content
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_page( $attrs = [], $content = null ) {
		// Load styles and scripts only on demand.
		wp_enqueue_script( 'wsb-all-events-scripts' );
		$this->add_theme_fonts();

		$attrs = $this->get_widget_attrs( $attrs );

		$method = 'events';
		$fields = 'title,location,hashed_id,schedule,free,type,registration_page,spoken_languages,sold_out,facilitators,free_ticket_type,paid_ticket_types';
		$query  = array(
			'future' => true,
			'public' => true,
			'fields' => $fields,
		);
		if ( ! is_null( $attrs['category'] ) ) {
			$query['categoryId'] = $attrs['category'];
		}
		$this->dict->set_schedule_attrs( $attrs );
		$response = $this->requests->get( $method, $query );

		return $this->render_list( $response, $attrs, $content );
	}

	/**
	 * Returns widget's attributes
	 *
	 * @param $attrs array User attributes
	 *
	 * @return array
	 */
	private function get_widget_attrs( $attrs ) {
		$defaults = array(
			'category' => null,
			'layout'   => $this->settings->get( WSB_Options::SCHEDULE_LAYOUT, 'table' ),
		);
		return shortcode_atts( $defaults, $attrs );
	}

	/**
	 * Renders the list of events
	 *
	 * @param $response WSB_Response
	 * @param $attrs array Widget's attributes
	 * @param $content string | null Content of the wsb_schedule shortcode
	 *
	 * @since  2.0.0
	 * @return string
	 */
	private function render_list( $response, $attrs, $content ) {
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

		if ( count( $events ) == 0 ) {
			return $this->settings->get( WSB_Options::SCHEDULE_NO_EVENTS );
		}

		$template_data = array(
			'events' => $events,
			'theme'  => $this->get_theme(),
		);
		if ( $content ) {
			$template = $content;
		} else {
			if ($this->get_list_type() == 'table') {
				$custom_template = $this->settings->get(WSB_Options::SCHEDULE_TABLE_TEMPLATE);
			} else {
				$custom_template = $this->settings->get(WSB_Options::SCHEDULE_TILE_TEMPLATE);
			}
			$template        = $this->get_template( 'schedule-page', $custom_template );
		}

		$this->dict->set_events( $events );
		$processed_template = do_shortcode( $template );
		$content            = $this->compile_string( $processed_template, $template_data );
		$this->dict->clear_events();

		return $this->add_custom_styles( $content );
	}

	/**
	 * Renders filters on the page
	 *
	 * @param array $attrs Short code attributes
	 *
	 * @since  0.3.0
	 * @return string
	 */
	protected function render_filters( $attrs = [] ) {
		$events = $this->dict->get_events();
		if ( $events === null ) {
			return '';
		}
		$template = $this->get_template( 'filters', null );
		if ( is_null( $template ) ) {
			return '';
		}
		$available_filters = array_map(
			function ( $name ) {
				return trim( $name );
			},
			explode( ',', $attrs['filters'] )
		);

		$event_filters = new Event_Filters( $events, $available_filters );
		return $this->compile_string( $template, array( 'filters' => $event_filters->get_filters() ) );
	}

	/**
	 * Returns default attributes for the shortcodes
	 *
	 * @param string $shortcode_name Name of the shortcode (only the meaningful part)
	 *
	 * @return array
	 */
	protected function get_default_attrs( $shortcode_name ) {
		switch ( $shortcode_name ) {
			case 'item':
				return array( 'cols' => 'schedule,location,title,register' );
			case 'filters':
				return array( 'filters' => 'location,trainer,language,type' );
			case 'register':
				return array( 'registration' => 'false' );
			case 'table_register':
				return array( 'registration' => 'false' );
			default:
				return array();
		}
	}

	/**
	 * Renders the list of events
	 *
	 * @param array       $attrs Short code attributes
	 * @param null|string $content Short code content
	 *
	 * @since  0.3.0
	 * @return string
	 */
	protected function render_item( $attrs = [], $content = null ) {
		$events = $this->dict->get_events();
		if ( is_null( $events ) ) {
			return '';
		}
		$item_template = $this->get_template( 'schedule/item', null );
		if ( ! $item_template ) {
			return '';
		}

		$html = '';
		foreach ( $events as $event ) {
			$this->dict->set_event( $event );
			$item_content           = $this->compile_string( $content, array( 'event' => $event ) );
			$processed_item_content = do_shortcode( $item_content );
			$html                  .= $this->compile_string(
				$item_template,
				array(
					'event'   => $event,
					'content' => $processed_item_content,
					'layout'  => $this->get_list_type(),
				)
			);
			$this->dict->clear_event();
		}

		$list_template = $this->get_template( 'schedule/layout', null );
		if ( ! $list_template ) {
			return '';
		}

		$attrs   = shortcode_atts( $this->get_default_attrs( 'item' ), $attrs );
		$columns = array_map(
			function ( $name ) {
				return trim( $name );
			},
			explode( ',', $attrs['cols'] )
		);

		$attrs['content'] = $html;
		$attrs['cols']    = $columns;
		$attrs['layout']  = $this->get_list_type();
		return $this->compile_string( $list_template, $attrs );
	}

	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name Name of the shortcode (like 'title', 'register')
	 * @param array       $attrs Attributes
	 * @param null|string $content Replaceable content
	 *
	 * @since 2.0.0
	 * @return string
	 */
	protected function render_simple_shortcode( $name, $attrs = [], $content = null ) {
		$event = $this->dict->get_event();
		if ( ! is_a( $event, 'Event' ) ) {
			return '';
		}
		$template = $this->get_template( 'schedule/' . $name, null );
		if ( ! $template ) {
			return '[wsb_schedule_' . $name . ']';
		}
		$attrs['event']     = $event;
		$attrs['layout']    = $this->get_list_type();
		$processed_template = do_shortcode( $template );

		return $this->compile_string( $processed_template, $attrs );
	}

	/**
	 * Returns the type of event list
	 *
	 * @since  2.0.0
	 * @return string
	 */
	private function get_list_type() {
		$attrs = $this->dict->get_schedule_attrs();
		if ( is_null( $attrs ) || is_null( $attrs['layout'] ) ) {
			return 'table';
		}
		return $attrs['layout'];
	}


	/**
	 * Handles 'wsb_schedule' shortcode
	 *
	 * @param $attrs   array  Shortcode attributes
	 * @param $content string Shortcode content
	 * @since  2.0.0
	 * @return string
	 */
	static public function page( $attrs, $content, $tag ) {
		$page = new WSB_Schedule_Page();
		return $page->render_page( $attrs, $content );
	}
}
