<?php
/**
 * The file that defines the class with the shortcode for 'Next event' element
 *
 * @link       https://workshopbutler.com
 * @since      2.12.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the 'Next event' shortcode
 *
 * @since      2.12.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Next_Event extends WSB_Page {

	/**
	 * Request entity
	 *
	 * @var WSB_Requests
	 * @since 2.12.0
	 */
	private $requests;

	/**
	 * Number of words between '_' in prefix (usually 2)
	 *
	 * @var int
	 * @since 2.12.0
	 */
	static protected $prefix_size = 3;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.12.0
	 */
	public function __construct() {
		parent::__construct();
		$this->load_dependencies();
		$this->requests = new WSB_Requests();
	}

	/**
	 * Load the required dependencies for this class.
	 *
	 * @since    2.12.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/../../includes/class-wsb-options.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-requests.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-list.php';
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
			'async'       => true,
			'category'    => null,
			'event_types' => null,
		);

		return shortcode_atts( $defaults, $attrs );
	}

	/**
	 * Renders the button (async)
	 *
	 * @param array       $attrs Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @return string
	 * @since  2.12.0
	 */
	public function render( $attrs = array(), $content = null ) {
		$this->add_theme_fonts();

		$attrs = $this->get_attrs( $attrs );

		$method = 'events';
		$query  = Event_List::prepare_query( $attrs, 1 );

		$response = $this->requests->get( $method, $query );
		if ( $response->is_error() ) {
			return $this->format_error( $response->error );
		}

		$events = Event_List::from_json( $response->body->data, $this->settings, false );

		if ( 0 === count( $events ) ) {
			$event = null;
		} else {
			$event = $events[0];
		}

		$template_data = array(
			'event' => $event,
			'theme' => $this->get_theme(),
		);

		if ( $content ) {
			$template = $content;
		} else {
			$template = $this->get_template( 'next-event/element', $content );
		}
		$this->dict->set_event( $event );
		$processed_template = do_shortcode( $template );
		$content            = $this->compile_string( $processed_template, $template_data );
		$this->dict->clear_event();

		return $content;
	}


	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name Name of the shortcode (like 'button').
	 * @param array       $attrs Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return string
	 * @since 2.12.0
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
		$event = $this->dict->get_event();
		if ( ! is_a( $event, 'WorkshopButler\Event' ) ) {
			$event = null;
		}
		$template = $this->get_template( 'next-event/' . $name, null );
		if ( ! $template ) {
			return '[wsb_next_event_' . $name . ']';
		}
		$attrs['event']     = $event;
		$processed_template = do_shortcode( $template );

		return $this->compile_string( $processed_template, $attrs );
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
			case 'button':
				return array(
					'registration'   => false,
					'target'         => '_self',
					'title'          => null,
					'no_event_title' => null,
				);
			default:
				return array();
		}
	}

	/**
	 * Renders the element
	 *
	 * @param array       $attrs Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @return string
	 * @since  2.12.0
	 */
	public static function element( $attrs = array(), $content = null ) {
		$element = new WSB_Next_Event();

		return $element->render( $attrs, $content );
	}
}
