<?php
/**
 * Field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Field $field
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

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
<?php if ( 'billing.country' === $field->get_name() ) { ?>
	<div id="wsb-form__billing-message" class="wsb-form__billing-message"></div>
<?php } ?>
<?php if ( 'ticket' === $field->get_type() && $field->tickets->validate_tax) { ?>
<div class="wsb-form__field" style="display:none;" id="wsb-form-tax-widget">
	<label class="wsb-label"><?php echo esc_html__( 'tax.widget.tax_id', 'wsbintegration' ); ?></label>
	<div class="wsb-form__tax-widget">
		<div class="wsb-form__tax-widget-input">
		<input name="tax_id" title="tax" type="text" data-tax-widget-value/>
		<input name="tax_intent_id" type="hidden" data-control data-tax-intent-id/>
		</div>
		<div class="wsb-form__tax-widget-buttons">
		<a class="wsb-form__tax-widget-apply" data-tax-widget-apply><?php echo esc_html__( 'tax.widget.apply', 'wsbintegration' ); ?></a>
		<a class="wsb-form__tax-widget-clear" data-tax-widget-clear><?php echo esc_html__( 'tax.widget.clear', 'wsbintegration' ); ?></a>
		</div>
		<div class="wsb-form__tax-widget-message"><div data-tax-widget-message></div></div>
	</div>
</div>
<?php } ?>
