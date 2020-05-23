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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		parent::__construct();
		$this->load_dependencies();
		$this->requests = new WSB_Requests();
		$this->load_templates();
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-list.php';
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
		$query  = Event_List::prepare_query( $attrs, - 1 );

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
			'category'        => null,
			'event_type'      => null,
			'layout'          => $this->settings->get( WSB_Options::SCHEDULE_LAYOUT, 'table' ),
			'wrapper'         => false,
			'only_featured'   => false,
			'featured_on_top' => $this->settings->is_featured_events_active() && $this->settings->get( WSB_Options::FEATURED_ON_TOP, false ),
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

		$events = Event_List::from_json( $response->body->data, $this->settings, $attrs['only_featured'] );
		if ( true === filter_var( $attrs['featured_on_top'], FILTER_VALIDATE_BOOLEAN ) ) {
			$events = Event_List::put_featured_on_top( $events );
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
					'tags'               => $this->settings->is_featured_events_active() ? 'all' : 'free',
					'highlight_featured' => $this->settings->is_featured_events_active(),
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

		$html       = '';
		$item_attrs = shortcode_atts( $this->get_default_attrs( 'item' ), $attrs );
		foreach ( $events as $event ) {
			$this->dict->set_event( $event );
			$this->dict->set_item_attrs( $item_attrs );
			$item_content           = $this->compile_string( $content, array( 'event' => $event ) );
			$processed_item_content = do_shortcode( $item_content );
			$item_attrs['event']    = $event;
			$item_attrs['content']  = $processed_item_content;
			$item_attrs['layout']   = $this->get_list_type();
			$html                  .= $this->compile_string( $item_template, $item_attrs );
			$this->dict->clear_event();
		}

		$list_template = $this->get_template( 'schedule/layout', null );
		if ( ! $list_template ) {
			return '';
		}

		$attrs            = shortcode_atts( $this->get_default_attrs( 'item' ), $attrs );
		$attrs['content'] = $html;
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
		$attrs['tags']      = $this->get_active_tags();
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
	 * Returns the types of active tags ('all', 'free', 'featured')
	 *
	 * @return string
	 * @since 2.12.0
	 */
	private function get_active_tags() {
		$attrs = $this->dict->get_item_attrs();
		if ( is_null( $attrs ) || is_null( $attrs['tags'] ) ) {
			return 'all';
		}

		return $attrs['tags'];
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
