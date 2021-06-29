<?php
/**
 * Registration form of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
$form = $event->registration_form;
?>
<div class="wsb-congratulation" id="wsb-success" style="display: none;">
	<h2 class="wsb-congratulation__title">
		<?= esc_html__( 'registration.successTitle', 'wsbintegration' ); ?>
	</h2>
	<div class="wsb-congratulation__p">
		<?= esc_html__( 'registration.successMsg', 'wsbintegration' ); ?>
	</div>
</div>

<form action="#" class="wsb-form" id="wsb-form">
	<div class="wsb-form__body">
		<?php
		if ( $form ) {
			if ( $form->get_instructions() ) {
				?>
				<div class="wsb-form__instructions"><?= esc_html( $form->get_instructions() ); ?></div>
				<?php
			}
		}
		foreach ( $form->get_sections() as $section ) {
			WSB()->dict->set_form_section( $section );
			if ( 'ticket' === $section->get_id() ) {
				do_action( 'wsb_registration_form_ticket_section' );
			} elseif ( 'payment' === $section->get_id() ) {
				?>
				<div class="wsb-form__payment-alert wsb-not-secure-alert">
					This page is not secure. Card payments are turned off.
				</div>
				<div class="wsb-form__payment-alert wsb-no-payment-method">
					No payment method is available. You cannot register to this event.
				</div>
				<?php
				do_action( 'wsb_registration_form_payment_section' );
			} else {
				?>
				<section>
					<?php if ( 'footer' !== $section->get_id() ) { ?>
						<div class="wsb-form__section-title">
							<?= esc_html__( strtolower( 'form.section.' . $section->get_id() ), 'wsbintegration' ); ?>
						</div>
						<?php
					}
					foreach ( $section->get_fields() as $field ) {
						WSB()->dict->set_form_field( $field );
						do_action( 'wsb_registration_form_field' );
						WSB()->dict->clear_form_field();
					}
					?>
				</section>
				<?php
			}
			WSB()->dict->clear_form_section();
		}
		?>
		<div class="wsb-form__error" data-form-major-error></div>
		<?php if ( $event->state->closed() ) { ?>
			<button class="wsb-form__btn"
					disabled><?= esc_html( Formatter::format( $event->state ) ); ?></button>
		<?php } else { ?>
			<button type="submit" class="wsb-form__btn" id="default-submit-button">
				<i class="fa fa-spinner fa-spin" style="display: none;"></i>
				<?= esc_html__( 'event.form.button', 'wsbintegration' ); ?>
			</button>
			<div id="paypal-button-container" style="display:none;"></div>
			<?php
		}
		?>
	</div>
</form>
