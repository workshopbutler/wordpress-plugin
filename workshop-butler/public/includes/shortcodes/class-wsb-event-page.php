<?php
/**
 * The file that defines the event page class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/log-error.php';
require_once WSB_ABSPATH . 'public/includes/config/class-single-event-config.php';

use WorkshopButler\Config\Single_Event_Config;

/**
 * Event Page class which handles the rendering and logic for the event page
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Event_Page extends WSB_Page {

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event.php';
	}

	/**
	 * Renders the event page
	 *
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public function render( $attrs = array(), $content = null ) {
		$id = get_query_var( 'id', 0 );
		if ( 0 === $id ) {
			log_error( 'WSB_Event_Page', 'Incorrect workshop ID', array() );

			return $this->format_error( 'Incorrect workshop ID' );
		}
		$may_be_event = $this->dict->get_event();
		if ( is_null( $may_be_event ) ) {
			$may_be_event = $this->requests->retrieve_event( $id );
		}
		if ( is_wp_error( $may_be_event ) ) {
			return $this->format_error( $may_be_event->get_error_message() );
		}
		wp_enqueue_script( 'wsb-event-page' );
		$this->add_theme_fonts();
		$this->add_localized_script( $may_be_event );

		$attrs = $this->get_attrs( $attrs );

		return $this->render_page( $may_be_event );
	}

	/**
	 * Adds a localized version of JS script on the page
	 *
	 * @param Event $event Event of interest.
	 */
	protected function add_localized_script( $event ) {
		$wsb_nonce = wp_create_nonce( 'wsb-nonce' );
		wp_localize_script(
			'wsb-event-page',
			'wsb_event',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => $wsb_nonce,
				'country'  => $event->location->country_code,
				'trainer'  => $event->trainers[0]->id,
				'type_id'  => $event->type->id,
				'id'       => $event->hashed_id,
			)
		);
	}

	/**
	 * Returns shortcodes's attributes
	 *
	 * @param array $attrs User attributes.
	 *
	 * @return array
	 */
	private function get_attrs( $attrs ) {

		$defaults = array(
			'wrapper' => false,
		);

		return shortcode_atts( $defaults, $attrs );
	}

	/**
	 * Renders the event page
	 *
	 * @param Event $event Event to render.
	 *
	 * @return string
	 */
	private function render_page( $event ) {
		$this->dict->set_event( $event );
		$this->dict->set_single_event_config( new Single_Event_Config() );
		if ( $this->settings->use_old_templates() ) {
			$content = $this->render_old_template( $event );
		} else {
			$content = $this->render_new_template();
		}
		$this->dict->clear_event();

		return $this->add_custom_styles( $content );
	}

	/**
	 * Controls the page shortcode
	 *
	 * @param array $attrs Shortcode attributes.
	 * @param null  $content Shortcode content.
	 *
	 * @return string
	 */
	public static function page( $attrs = array(), $content = null ) {
		$page = new WSB_Event_Page();

		return $page->render( $attrs, $content );
	}

	/**
	 * Render the event using new templates
	 *
	 * @return false|string
	 * @since 3.0.0
	 */
	protected function render_new_template() {
		$event = WSB()->dict->get_event();
		if( !is_a( $event, 'WorkshopButler\Event' )) {
			return false;
		}

		ob_start();
		wsb_get_template( 'single-event.php', array(
			'theme' => $this->get_theme(),
			'event' => $event,
			'config' => WSB()->dict->get_single_event_config(),
		));
		return ob_get_clean();
	}

	/**
	 * Renders the old event page
	 *
	 * @param Event $event Current event.
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function render_old_template( $event ): string {
		$custom_template = $this->settings->get( WSB_Options::EVENT_TEMPLATE );
		$template        = $this->get_template( 'event-page', $custom_template );

		$template_data = array(
			'event' => $event,
			'theme' => $this->get_theme(),
		);

		$processed_template = do_shortcode( $template );

		return $this->compile_string( $processed_template, $template_data );
	}
}
