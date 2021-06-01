<?php
/**
 * Set of hooks to render event registration form
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

require_once WSB_ABSPATH . '/includes/wsb-core-functions.php';
require_once WSB_ABSPATH . '/includes/wsb-conditional-functions.php';

/**
 * Class Registration_Form_Hooks
 *
 * @since 3.0.0
 * @package WorkshopButler\Hooks
 */
class Registration_Form_Hooks {

	/**
	 * Initializes hooks available in this class
	 */
	public static function init() {
		add_action( 'wsb_registration_form', array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'form_content' ), 10 );
		add_action(
			'wsb_registration_form_ticket_section',
			array(
				'WorkshopButler\Hooks\Registration_Form_Hooks',
				'ticket_section',
			),
			10
		);
		add_action(
			'wsb_registration_form_payment_section',
			array(
				'WorkshopButler\Hooks\Registration_Form_Hooks',
				'payment_section',
			),
			10
		);
		add_action( 'wsb_registration_form_field', array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'field' ), 10 );
		add_action(
			'wsb_registration_form_input_field',
			array(
				'WorkshopButler\Hooks\Registration_Form_Hooks',
				'input_field',
			),
			10
		);
		add_action( 'wsb_registration_form_label', array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'label' ), 10 );
		add_action( 'wsb_registration_form_textarea_field', array( 'WorkshopButler\Hooks\Registration_Form_Hooks',
																   'textarea_field'
		), 10 );
		add_action( 'wsb_registration_form_checkbox_field', array( 'WorkshopButler\Hooks\Registration_Form_Hooks',
																   'checkbox_field'
		), 10 );
		add_action( 'wsb_registration_form_country_field', array( 'WorkshopButler\Hooks\Registration_Form_Hooks',
																  'country_field'
		), 10 );
		add_action( 'wsb_registration_form_select_field', array( 'WorkshopButler\Hooks\Registration_Form_Hooks',
																  'select_field'
		), 10 );
		add_action( 'wsb_registration_form_ticket_field', array( 'WorkshopButler\Hooks\Registration_Form_Hooks',
																 'ticket_field'
		), 10 );
	}

	/**
	 * Renders form content for a registration form
	 *
	 * @see Registration_Form_Hooks::init() for the hook
	 */
	public static function form_content() {
		wsb_get_template( 'registration/form.php' );
	}

	/**
	 * Renders the label for a registration form's field
	 *
	 * @see Registration_Form_Hooks::init() for the hook
	 */
	public static function label() {
		wsb_get_template( 'registration/label.php' );
	}

	/**
	 * Renders textarea for a registration form
	 *
	 * @see Registration_Form_Hooks::init() for the hook
	 */
	public static function textarea_field() {
		wsb_get_template( 'registration/textarea.php' );
	}

	/**
	 * Renders the event's trainers
	 */
	public static function field() {
		wsb_get_template( 'registration/field.php' );
	}

	/**
	 * Renders the form's payment section
	 */
	public static function payment_section() {
		wsb_get_template( 'registration/payment-section.php' );
	}

	/**
	 * Renders the input field
	 */
	public static function input_field() {
		wsb_get_template( 'registration/input.php' );
	}

	/**
	 * Renders the form's ticket section
	 */
	public static function ticket_section() {
		wsb_get_template( 'registration/ticket-section.php' );
	}

	/**
	 * Renders the form's checkbox
	 */
	public static function checkbox_field() {
		wsb_get_template( 'registration/checkbox.php' );
	}

	/**
	 * Renders the form's list of countries
	 */
	public static function country_field() {
		wsb_get_template( 'registration/country.php' );
	}

	/**
	 * Renders the form's select field
	 */
	public static function select_field() {
		wsb_get_template( 'registration/select.php' );
	}

	/**
	 * Renders the form's ticket field
	 */
	public static function ticket_field() {
		wsb_get_template( 'registration/ticket.php' );
	}
}
