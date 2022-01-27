<?php
/**
 * Registration form of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$form = $event->registration_form;
if ( !is_a( $form, 'WorkshopButler\Form' ) ) {
	return;
}
?>
<div class="wsb-congratulation" id="wsb-success">
	<h2 class="wsb-congratulation__title">
		<?php echo esc_html__( 'registration.successTitle', 'wsbintegration' ); ?>
	</h2>
	<div class="wsb-congratulation__p">
		<?php echo esc_html__( 'registration.successMsg', 'wsbintegration' ); ?>
	</div>
</div>

<form action="#" class="wsb-form" id="wsb-form">
	<div class="wsb-form__body">
		<?php if ( $form->get_instructions() ) { ?>
			<div class="wsb-form__instructions"><?php echo esc_html( $form->get_instructions() ); ?></div>
		<?php } ?>
		<?php do_action( 'wsb_registration_form_sections' ); ?>
		<div class="wsb-form__error" data-form-major-error></div>
		<?php if ( $event->state->closed() ) { ?>
			<button class="wsb-form__btn"
					disabled><?php echo esc_html( Formatter::format( $event->state ) ); ?></button>
		<?php } else { ?>
			<button type="submit" class="wsb-form__btn" id="default-submit-button">
				<i class="fa fa-spinner fa-spin" style="display: none;"></i>
				<?php echo esc_html__( 'event.form.button', 'wsbintegration' ); ?>
			</button>
			<div id="paypal-button-container" style="display:none;"></div>
			<?php
		}
		?>
	</div>
</form>
