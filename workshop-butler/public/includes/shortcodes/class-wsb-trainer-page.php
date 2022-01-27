<?php
/**
 * The file that defines the trainer page class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Trainer Page class which handles the rendering and logic for the profile of trainer
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer_Page extends WSB_Page {

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-requests.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-trainer.php';
	}

	/**
	 * Renders the trainer page
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
			return $this->format_error( 'empty trainer ID' );
		}
		$id             = sanitize_text_field( $_GET['id'] );
		$may_be_trainer = $this->dict->get_trainer();
		if ( is_null( $may_be_trainer ) ) {
			$may_be_trainer = $this->requests->retrieve_trainer( $id );
		}
		if ( is_wp_error( $may_be_trainer ) ) {
			return $this->format_error( $may_be_trainer->get_error_message() );
		}

		wp_enqueue_script( 'wsb-single-trainer-scripts' );
		$this->add_theme_fonts();
		$wsb_nonce = wp_create_nonce( 'wsb-nonce' );

		wp_localize_script(
			'wsb-single-trainer-scripts',
			'wsb_single_trainer',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'nonce'      => $wsb_nonce,
				'trainer_id' => $id,
			)
		);

		return $this->render_profile( $may_be_trainer );
	}

	/**
	 * Renders the profile of trainer
	 *
	 * @param Trainer $trainer Current trainer.
	 *
	 * @return string
	 */
	private function render_profile( $trainer ) {
		$this->dict->set_trainer( $trainer );
		if ( $this->settings->use_old_templates() ) {
			$content = $this->render_old_template( $trainer );
		} else {
			$content = $this->render_new_template();
		}
		$this->dict->clear_trainer();

		return $this->add_custom_styles( $content );
	}

	/**
	 * Handles 'wsb_trainer' shortcode
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @since  2.0.0
	 * @return string
	 */
	public static function page( $attrs = array(), $content = null ) {
		$page = new WSB_Trainer_Page();

		return $page->render( $attrs, $content );
	}

	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name    Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs   Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return string
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
		$trainer = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'WorkshopButler\Trainer' ) ) {
			return '';
		}
		$template = $this->get_template( 'trainer/' . $name, null );
		if ( ! $template ) {
			return '[wsb_trainer_' . $name . ']';
		}
		$attrs['trainer'] = $trainer;
		return $this->compile_string( $template, $attrs );
	}


	/**
	 * Render the event using new templates
	 *
	 * @return false|string
	 * @since 3.0.0
	 */
	protected function render_new_template() {
		$trainer = WSB()->dict->get_trainer();
		if( !is_a( $trainer, 'WorkshopButler\Trainer' )) {
			return false;
		}

		ob_start();
		wsb_get_template( 'single-trainer.php', array(
			'theme' => $this->get_theme(),
			'trainer' => $trainer,
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
	private function render_old_template( $trainer ): string {
		$template_data = array(
			'trainer' => $trainer,
			'theme'   => $this->get_theme(),
		);

		$custom_template = $this->settings->get( WSB_Options::TRAINER_TEMPLATE );
		$template        = $this->get_template( 'trainer-page', $custom_template );

		$processed_template = do_shortcode( $template );
		return $this->compile_string( $processed_template, $template_data );
	}
}
