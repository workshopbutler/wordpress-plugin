<?php
/**
 * Field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Select;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$field = WSB()->dict->get_form_field();
is_a( $field, 'WorkshopButler\Field' ) || exit();

?>

<div class="wsb-form__field <?php echo esc_attr( $field->get_type() ); ?>"
		id="<?php echo esc_attr( 'wsb-form-field-' . $field->get_name() ); ?>">
	<?php
	do_action( 'wsb_registration_form_label' );
	if ( 'textarea' === $field->get_type() ) {
		do_action( 'wsb_registration_form_textarea_field' );
	} elseif ( 'select' === $field->get_type() ) {
		do_action( 'wsb_registration_form_select_field' );
	} elseif ( 'checkbox' === $field->get_type() ) {
		do_action( 'wsb_registration_form_checkbox_field' );
	} elseif ( 'country' === $field->get_type() ) {
		do_action( 'wsb_registration_form_country_field' );
	} elseif ( 'ticket' === $field->get_type() ) {
		do_action( 'wsb_registration_form_ticket_field' );
	} else {
		do_action( 'wsb_registration_form_input_field' );
	}
	?>
</div>
