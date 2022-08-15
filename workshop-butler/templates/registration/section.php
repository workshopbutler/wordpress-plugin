<?php
/**
 * Registration form of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Section $section
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( 'ticket' === $section->get_id() ) { ?>
	<section class="wsb-ticket-section">
		<div class="wsb-form__section-title">
			<?php echo esc_html__( strtolower( 'form.section.' . $section->get_id() ), 'wsbintegration' ); ?>
		</div>
		<?php do_action( 'wsb_registration_form_fields' ); ?>
	</section>
<?php } elseif ( 'payment' === $section->get_id() ) { ?>
	<div class="wsb-form__payment-alert wsb-not-secure-alert">
		This page is not secure. Card payments are turned off.
	</div>
	<div class="wsb-form__payment-alert wsb-no-payment-method">
		No payment method is available. You cannot register to this event.
	</div>
	<section class="wsb-ticket-section" data-payment-section>
		<?php do_action( 'wsb_registration_form_fields' ); ?>
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
<?php } else { ?>
	<section class="wsb-form-section__<?php echo esc_attr( $section->get_id() ); ?>">
		<?php if ( 'footer' !== $section->get_id() ) { ?>
			<div class="wsb-form__section-title">
				<?php echo esc_html__( strtolower( 'form.section.' . $section->get_id() ), 'wsbintegration' ); ?>
			</div>
		<?php } ?>
		<?php do_action( 'wsb_registration_form_fields' ); ?>
	</section>
	<?php
}
