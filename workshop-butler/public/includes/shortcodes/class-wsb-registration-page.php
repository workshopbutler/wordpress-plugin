<?php
/**
 * The file that defines the event registration page class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

use WorkshopButler\Config\Single_Event_Config;
use WorkshopButler\View\Countries;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/log-error.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class-countries.php';

/**
 * Event Page class which handles the rendering and logic for the event page
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Registration_Page extends WSB_Page {

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
	 * Loads templates used later in the other templates
	 *
	 * @deprecated
	 * @since 2.7.0
	 */
	private function load_templates() {
		$field           = $this->get_template( 'registration/field', null );
		$label           = $this->get_template( 'registration/label', null );
		$input           = $this->get_template( 'registration/input', null );
		$ticket          = $this->get_template( 'registration/ticket', null );
		$ticket_section  = $this->get_template( 'registration/ticket-section', null );
		$payment_section = $this->get_template( 'registration/payment-section', null );
		$this->twig->loader->setTemplate( 'field.twig', $field );
		$this->twig->loader->setTemplate( 'label.twig', $label );
		$this->twig->loader->setTemplate( 'input.twig', $input );
		$this->twig->loader->setTemplate( 'ticket.twig', $ticket );
		$this->twig->loader->setTemplate( 'ticket-section.twig', $ticket_section );
		$this->twig->loader->setTemplate( 'payment-section.twig', $payment_section );
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
	 * Renders the registration page
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
			log_error( 'WSB_Registration_Page', 'Incorrect workshop ID', array() );

			return $this->format_error( 'Incorrect workshop ID' );
		}
		$may_be_event = $this->dict->get_event();
		if ( is_null( $may_be_event ) ) {
			$may_be_event = $this->requests->retrieve_event( $id );
		}
		if ( is_wp_error( $may_be_event ) ) {
			return $this->format_error( $may_be_event->get_error_message() );
		}
		wp_enqueue_script( 'wsb-registration-page' );
		if ( $may_be_event->card_payment ) {
			wp_enqueue_script( 'stripe' );
			$this->add_card_payment_config( $may_be_event );
		}
		if ( $may_be_event->paypal_payment ) {
			$this->add_paypal_payment_config( $may_be_event );
		}

		$this->add_theme_fonts();
		$this->add_localized_script( $may_be_event );

		return $this->render_page( $may_be_event );
	}

	/**
	 * Returns true if the card payment is in test mode
	 *
	 * @param CardPayment $card_payment CardPayment configuration.
	 *
	 * @return bool
	 */
	protected function is_test( $card_payment ) {
		return strpos( $card_payment->stripe_public_key, 'pk_test_' ) === 0;
	}

	/**
	 * Adds a localized version of JS script on the page
	 *
	 * @param Event $event Event.
	 */
	protected function add_localized_script( $event ) {
		$wsb_nonce = wp_create_nonce( 'wsb-nonce' );
		wp_localize_script(
			'wsb-registration-page',
			'wsb_event',
			array(
				'ajax_url'                 => admin_url( 'admin-ajax.php' ),
				'nonce'                    => $wsb_nonce,
				'is_registration_closed'   => $event->state->closed(),
				'id'                       => $event->id,
				'hashed_id'                => $event->hashed_id,
				'eu_countries'             => Countries::getEUCodes(),
				'error_required'           => __( 'form.error.required', 'wsbintegration' ),
				'error_email'              => __( 'form.error.email', 'wsbintegration' ),
				'error_url'                => __( 'form.error.url', 'wsbintegration' ),
				'error_date'               => __( 'form.error.date', 'wsbintegration' ),
				'error_nospace'            => __( 'form.error.number', 'wsbintegration' ),
				'error_digits'             => __( 'form.error.digits', 'wsbintegration' ),
				'error_attendee_exist'     => __( 'form.error.attendee', 'wsbintegration' ),
				'string_validation_errors' => __( 'Validation errors occurred. Please confirm the fields and try again.', 'wsbintegration' ),
				'string_error_try_again'   => __( 'The server doesn\'t response. Please try again. If the error persists please contact your trainer.', 'wsbintegration' ),
				'string_try_again'         => __( 'Please try again. If the error persists please contact your trainer.', 'wsbintegration' ),
			)
		);
	}

	/**
	 * Adds card payment configuration to the registration page
	 *
	 * @param Event $event Event.
	 *
	 * @since 2.14.0
	 */
	protected function add_card_payment_config( $event ) {
		if ( $event->card_payment ) {
			wp_localize_script(
				'wsb-registration-page',
				'wsb_payment',
				array(
					'active'            => $event->card_payment->active,
					'free'              => $event->free,
					'test'              => $this->is_test( $event->card_payment ),
					'stripe_public_key' => $event->card_payment->stripe_public_key,
					'stripe_client_id'  => $event->card_payment->stripe_client_id,
				)
			);
		}
	}

	/**
	 * Adds PayPal payment configuration to the registration page
	 *
	 * @param Event $event Event.
	 *
	 * @since 2.14.0
	 */
	protected function add_paypal_payment_config( $event ) {
		if ( $event->paypal_payment ) {
			wp_localize_script(
				'wsb-registration-page',
				'wsb_paypal_payment',
				array(
					'active'    => $event->paypal_payment->active,
					'free'      => $event->free,
					'client_id' => $event->paypal_payment->client_id,
				)
			);
		}
	}

	/**
	 * Renders the registration form page
	 *
	 * @param Event $event Event.
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
	 * Render the registration form page using new templates
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
		wsb_get_template( 'registration-page.php', array(
			'theme' => $this->get_theme(),
			'event' => $event,
		));
		return ob_get_clean();
	}

	/**
	 * Renders the old registration form page
	 *
	 * @param Event $event Current event.
	 *
	 * @return string
	 * @deprecated
	 * @since 3.0.0
	 */
	private function render_old_template( $event ): string {
		$custom_template = $this->settings->get( WSB_Options::REGISTRATION_TEMPLATE );
		$template        = $this->get_template( 'registration-page', $custom_template );

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
	 * @param string      $name Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return string
	 * @deprecated
	 * @since 2.0.0
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
		$event = $this->dict->get_event();
		if ( ! is_a( $event, 'WorkshopButler\Event' ) ) {
			return '';
		}
		$template = $this->get_template( 'registration/' . $name, null );
		if ( ! $template ) {
			return '[wsb_registration_' . $name . ']';
		}
		$attrs['event']     = $event;
		$attrs['countries'] = Countries::get();

		return $this->compile_string( $template, $attrs );
	}

	/**
	 * Renders the registration page
	 *
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public static function page( $attrs = array(), $content = null ) {
		$page = new WSB_Registration_Page();

		return $page->render( $attrs, $content );
	}
}
