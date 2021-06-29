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

use WorkshopButler\Config\Event_Calendar_Config;
use WorkshopButler\Config\Next_Event_Config;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';
require_once WSB_ABSPATH . 'public/includes/config/class-next-event-config.php';
require_once WSB_ABSPATH . 'public/includes/config/class-event-calendar-config.php';

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
			'categories'     => null,
			'event_types'    => null,
			'registration'   => false,
			'target'         => '_self',
			'title'          => null,
			'no_event_title' => null,
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
		$config = new Event_Calendar_Config( $attrs );
		$query  = Event_List::prepare_query( $config, 1 );

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

		$this->dict->set_event( $event );
		$processed_attrs = is_array( $attrs ) ? self::convert_booleans( $attrs ) : array();

		$with_default_values = shortcode_atts( $this->get_default_attrs( 'button' ), $processed_attrs );
		$this->dict->set_next_event_config( new Next_Event_Config( $with_default_values ) );

		if ( $this->settings->use_old_templates() ) {
			$content = $this->render_old_template( $event, $content );
		} else {
			$content = $this->render_new_template();
		}
		$this->dict->clear_event();

		return $content;
	}

	/**
	 * Render the Next Event element using the new template
	 *
	 * @return false|string
	 * @since 3.0.0
	 */
	protected function render_new_template() {
		ob_start();
		wsb_get_template( 'next-event.php', array(
			'theme' => $this->get_theme(),
			'event' => WSB()->dict->get_event(),
			'config' => WSB()->dict->get_next_event_config(),
		));
		return ob_get_clean();
	}

	/**
	 * Renders the old Next Event element
	 *
	 * @param Event|null  $event Current event.
	 * @param string|null $content May be content of the shortcode.
	 *
	 * @return string
	 * @deprecated
	 * @since 3.0.0
	 */
	private function render_old_template( $event, $content ): string {
		if ( $content ) {
			$template = $content;
		} else {
			$template = $this->get_template( 'next-event/element', $content );
		}
		$template_data = array(
			'event' => $event,
			'theme' => $this->get_theme(),
		);

		$processed_template = do_shortcode( $template );

		return $this->compile_string( $processed_template, $template_data );
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
