<?php
/**
 * The file that defines the Schedule class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Schedule page class which handles the rendering and logic for the list of events
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Schedule_Page extends WSB_Page {

	/**
	 * Request entity
	 *
	 * @var WSB_Requests
	 */
	private $requests;

	/**A
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
	 * @param array       $attrs Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public function render_page( $attrs = array(), $content = null ) {
		// Load styles and scripts only on demand.
		$this->add_localized_script();
		$this->add_theme_fonts();

		$attrs = $this->get_attrs( $attrs );

		$method = 'events';
		$fields = 'title,location,hashed_id,schedule,free,type,registration_page,spoken_languages,sold_out,facilitators,free_ticket_type,paid_ticket_types,title_url';
		$query  = array(
			'dates'    => 'future',
			'public'   => true,
			'fields'   => $fields,
			'per_page' => '-1',
		);
		if ( ! is_null( $attrs['category'] ) ) {
			$query['categoryId'] = $attrs['category'];
		}
		if ( ! is_null( $attrs['event_type'] ) ) {
			$query['typeIds'] = $attrs['event_type'];
		}

		$this->dict->set_schedule_attrs( $attrs );
		$response = $this->requests->get( $method, $query );

		return $this->render_list( $response, $attrs, $content );
	}

	/**
	 * Adds a localized version of JS script on the page
	 *
	 * @since 2.12.0
	 */
	protected function add_localized_script() {
		wp_enqueue_script( 'wsb-all-events-scripts' );
		wp_localize_script(
			'wsb-all-events-scripts',
			'wsb',
			array(
				WSB_Options::FILTER_LOCATION_ID => WSB_Options::get_option( WSB_Options::SCHEDULE_LOCATION, WSB_Options::FILTER_LOCATION_ID ),
				WSB_Options::FILTER_LANGUAGE_ID => WSB_Options::get_option( WSB_Options::SCHEDULE_LANGUAGE, WSB_Options::FILTER_LANGUAGE_ID ),
				WSB_Options::FILTER_TRAINER_ID  => WSB_Options::get_option( WSB_Options::SCHEDULE_TRAINER, WSB_Options::FILTER_TRAINER_ID ),
				WSB_Options::FILTER_TYPE_ID     => WSB_Options::get_option( WSB_Options::SCHEDULE_TYPE, WSB_Options::FILTER_TYPE_ID ),
			)
		);
	}

	/**
	 * Returns widget's attributes
	 *
	 * @param array $attrs User attributes.
	 *
	 * @return array
	 */
	private function get_attrs( $attrs ) {

		$defaults = array(
			'category'      => null,
			'event_type'    => null,
			'layout'        => $this->settings->get( WSB_Options::SCHEDULE_LAYOUT, 'table' ),
			'wrapper'       => false,
			'only_featured' => false,
		);

		return shortcode_atts( $defaults, $attrs );
	}

	/**
	 * Renders the list of events
	 *
	 * @param WSB_Response  $response Workshop Butler API response.
	 * @param array         $attrs Shortcodes's attributes.
	 * @param string | null $content Content of the wsb_schedule shortcode.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	private function render_list( $response, $attrs, $content ) {
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
			if ( ! $attrs['only_featured'] || $event->featured ) {
				array_push( $events, $event );
			}
		}

		if ( 0 === count( $events ) ) {
			return $this->settings->get( WSB_Options::SCHEDULE_NO_EVENTS );
		}

		$template_data = array(
			'events' => $events,
			'theme'  => $this->get_theme(),
		);

		if ( $content ) {
			$template = $content;
		} else {
			if ( 'table' === $this->get_list_type() ) {
				$custom_template = $this->settings->get( WSB_Options::SCHEDULE_TABLE_TEMPLATE );
			} else {
				$custom_template = $this->settings->get( WSB_Options::SCHEDULE_TILE_TEMPLATE );
			}
			$template = $this->get_template( 'schedule-page', $custom_template );
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
	 * @param array $attrs Short code attributes.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	protected function render_filters( $attrs = array() ) {
		$events = $this->dict->get_events();
		if ( null === $events ) {
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
	 * @param string $shortcode_name Name of the shortcode (only the meaningful part).
	 *
	 * @return array
	 */
	protected function get_default_attrs( $shortcode_name ) {
		switch ( $shortcode_name ) {
			case 'image':
				return array(
					'without_url' => 'false',
					'width'       => '300',
					'height'      => '200',
				);
			case 'item':
				return array(
					'cols'         => 'schedule,location,title,register',
					'trainer_name' => 'true',
				);
			case 'filters':
				return array( 'filters' => 'location,trainer,language,type' );
			case 'register':
			case 'table_register':
				return array( 'registration' => 'false' );
			case 'time':
				return array(
					'timezone'        => 'online',
					'timezone_format' => 'short',
				);
			case 'title':
				return array(
					'truncate' => '60',
				);
			case 'trainers':
				return array( 'name' => 'true' );
			default:
				return array();
		}
	}

	/**
	 * Renders the list of events
	 *
	 * @param array       $attrs Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	protected function render_item( $attrs = array(), $content = null ) {
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
			$html                   .= $this->compile_string(
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
	 * @param string      $name Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
		$event = $this->dict->get_event();
		if ( ! is_a( $event, 'WorkshopButler\Event' ) ) {
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
	 * @return string
	 * @since  2.0.0
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
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public static function page( $attrs, $content ) {
		$page = new WSB_Schedule_Page();

		return $page->render_page( $attrs, $content );
	}
}
