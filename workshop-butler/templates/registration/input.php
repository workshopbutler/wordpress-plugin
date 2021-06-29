<?php
/**
 * Input field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$field = WSB()->dict->get_form_field();
is_a( $field, 'WorkshopButler\Field' ) || exit();
$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

if ( 'promo_code' === $field->get_name() ) { ?>
	<div class="wsb-promo-block">
		<a href="#" class="wsb-promo-link" data-promo-link>
			<?= esc_html__( 'form.promo.caption', 'wsbintegration' ); ?>
		</a>
		<input class="wsb-promo-code" data-promo-code
				name="<?= esc_attr( $field->get_name() ); ?>" data-control
				title="<?= esc_attr( $field->get_label() ); ?>"
				type="<?= esc_attr( $field->get_type() ); ?>"
			<?php
			if ( $field->is_required() ) {
				echo 'required';
			}
			if ( $event->state->closed() ) {
				echo 'disabled';
			}
			?>
		/>
	</div>
<?php } else { ?>
	<input name="<?= esc_attr( $field->get_name() ); ?>" data-control
			title="<?= esc_attr( $field->get_label() ); ?>"
			type="<?= esc_attr( $field->get_type() ); ?>"
		<?php
		if ( $field->is_required() ) {
			echo 'required';
		}
		if ( $event->state->closed() ) {
			echo 'disabled';
		}
		?>
	/>
	<?php
}
