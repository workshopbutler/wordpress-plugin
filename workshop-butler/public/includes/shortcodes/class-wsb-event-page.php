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
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @since  2.0.0
	 *
	 * @return string
	 */
	public function render( $attrs = array(), $content = null ) {
		if ( empty( $_GET['id'] ) ) {
			return $this->format_error( 'empty event ID' );
		}
		$may_be_event = $this->dict->get_event();
		if ( is_null( $may_be_event ) ) {
			$may_be_event = $this->requests->retrieve_event( $_GET['id'] );
		}
		if ( is_wp_error( $may_be_event ) ) {
			return $this->format_error( $may_be_event->get_error_message() );
		}
		wp_enqueue_script( 'wsb-event-page' );
		$this->add_theme_fonts();
		$this->add_localized_script( $may_be_event );

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
				'id'       => $event->hashed_id,
			)
		);
	}

	/**
	 * Renders the event page
	 *
	 * @param Event $event Event to render.
	 *
	 * @return string
	 */
	private function render_page( $event ) {
		$custom_template = $this->settings->get( WSB_Options::EVENT_TEMPLATE );
		$template        = $this->get_template( 'event-page', $custom_template );

		$template_data = array(
			'event' => $event,
			'theme' => $this->get_theme(),
		);

		$processed_template = do_shortcode( $template );
		$content            = $this->compile_string( $processed_template, $template_data );
		return $this->add_custom_styles( $content );
	}

	/**
	 * Controls the page shortcode
	 *
	 * @param array $attrs   Shortcode attributes.
	 * @param null  $content Shortcode content.
	 * @return string
	 */
	public static function page( $attrs = array(), $content = null ) {
		$page = new WSB_Event_Page();

		return $page->render( $attrs, $content );
	}
}
