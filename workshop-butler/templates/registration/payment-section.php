<?php
/**
 * Payment section of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$section = WSB()->dict->get_form_section();
is_a( $section, 'WorkshopButler\Section' ) || exit();

?>
<section class="wsb-ticket-section" data-payment-section>
	<?php
	foreach ( $section->get_fields() as $field ) {
		WSB()->dict->set_form_field( $field );
		do_action( 'wsb_registration_form_field' );
		WSB()->dict->clear_form_field();
	}
	?>
	<div class="wsb-form__field wsb-form__payment" data-card-section style="display:none;">
		<label class="wsb-label"></label>
		<div class="wsb-form__payment-holder">
			<div class="form-control wsb-form__card">
				<div id="stripe-placeholder"></div>
			</div>
			<div id="register-form-error" class="wsb-form__error"></div>
		</div>
	</div>
</section>
