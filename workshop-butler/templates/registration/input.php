<?php
/**
 * Input field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Field $field
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( 'promo_code' === $field->get_name() ) { ?>
	<div class="wsb-promo-block">
		<a href="#" class="wsb-promo-link" data-promo-link>
			<?php echo esc_html__( 'form.promo.caption', 'wsbintegration' ); ?>
		</a>
		<input class="wsb-promo-code" data-promo-code
				name="<?php echo esc_attr( $field->get_name() ); ?>" data-control
				title="<?php echo esc_attr( $field->get_label() ); ?>"
				type="<?php echo esc_attr( $field->get_type() ); ?>"
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
	<input name="<?php echo esc_attr( $field->get_name() ); ?>" data-control
			title="<?php echo esc_attr( $field->get_label() ); ?>"
			type="<?php echo esc_attr( $field->get_type() ); ?>"
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
