<?php
/**
 * Set of hooks to render event registration form
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

require_once WSB_ABSPATH . '/includes/wsb-core-functions.php';

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
		add_action(
			'wsb_registration_form',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'form_content' ),
			10
		);
		add_action(
			'wsb_registration_form_fields',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'fields' ),
			10
		);
		add_action(
			'wsb_registration_form_input_field',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'input_field' ),
			10
		);
		add_action(
			'wsb_registration_form_label',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'label' ),
			10
		);
		add_action(
			'wsb_registration_form_textarea_field',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'textarea_field' ),
			10
		);
		add_action(
			'wsb_registration_form_checkbox_field',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'checkbox_field'	),
			10
		);
		add_action(
			'wsb_registration_form_country_field',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'country_field' ),
			10
		);
		add_action(
			'wsb_registration_form_select_field',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'select_field' ),
			10
		);
		add_action(
			'wsb_registration_form_ticket_field',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'ticket_field' ),
			10
		);
		add_action(
			'wsb_registration_form_sections',
			array( 'WorkshopButler\Hooks\Registration_Form_Hooks', 'form_sections' ),
			10
		);
	}

	/**
	 * Renders form content for a registration form
	 *
	 * @see Registration_Form_Hooks::init() for the hook
	 */
	public static function form_content() {
		$event = WSB()->dict->get_event();
		if( !is_a( $event, 'WorkshopButler\Event' )) {
			return false;
		}
		wsb_get_template( 'registration/form.php', array(
			'event' => $event,
		));
	}

	/**
	 * Renders the label for a registration form's field
	 *
	 * @see Registration_Form_Hooks::init() for the hook
	 */
	public static function label() {
		Registration_Form_Hooks::with_field( 'registration/label.php' );
	}

	/**
	 * Renders textarea for a registration form
	 *
	 * @see Registration_Form_Hooks::init() for the hook
	 */
	public static function textarea_field() {
		Registration_Form_Hooks::with_field( 'registration/textarea.php' );
	}

	/**
	 * Renders form fields
	 */
	public static function fields() {
		foreach ( WSB()->dict->get_form_section()->get_fields() as $field ) {
			WSB()->dict->set_form_field( $field );
			wsb_get_template( 'registration/field.php', array(
				'field' => $field,
			) );
			WSB()->dict->clear_form_field();
		}
	}

	/**
	 * Renders the input field
	 */
	public static function input_field() {
		Registration_Form_Hooks::with_field( 'registration/input.php' );
	}

	/**
	 * Renders the form's checkbox
	 */
	public static function checkbox_field() {
		Registration_Form_Hooks::with_field( 'registration/checkbox.php' );
	}

	/**
	 * Renders the form's list of countries
	 */
	public static function country_field() {
		Registration_Form_Hooks::with_field( 'registration/country.php' );
	}

	/**
	 * Renders the form's select field
	 */
	public static function select_field() {
		Registration_Form_Hooks::with_field( 'registration/select.php' );
	}

	/**
	 * Renders the form's ticket field
	 */
	public static function ticket_field() {
		Registration_Form_Hooks::with_field( 'registration/ticket.php' );
	}

	/**
	 * Renders form sections
	 */
	public static function form_sections() {
		$event = WSB()->dict->get_event();
		foreach ( $event->registration_form->get_sections() as $section ) {
			WSB()->dict->set_form_section( $section );
			wsb_get_template( 'registration/section.php', array(
				'event' => $event,
				'section' => $section,
			) );
			WSB()->dict->clear_form_section();
		}
	}

	private static function with_field( $template ) {
		$event = WSB()->dict->get_event();
		if( !is_a( $event, 'WorkshopButler\Event' )) {
			return false;
		}

		wsb_get_template( $template, array(
			'event' => $event,
			'field' => WSB()->dict->get_form_field(),
		));
	}
}
