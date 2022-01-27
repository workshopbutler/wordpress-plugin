<?php
/**
 * Checkbox field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Field $field
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<input type="checkbox" value="yes" data-control
		name="<?php echo esc_attr( $field->get_name() ); ?>"
		title="<?php echo esc_attr( wp_strip_all_tags( $field->get_label() ) ); ?>"
	<?php
	if ( $field->is_required() ) {
		echo 'required';
	}
	if ( $event->state->closed() ) {
		echo 'disabled';
	}
	?>
/>
<label class="wsb-checkbox" for="<?php echo esc_attr( $field->get_name() ); ?>">
	<?php echo wp_kses_post( $field->get_label() ); ?>
</label>
