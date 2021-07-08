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
		name="<?= esc_attr( $field->get_name() ); ?>"
		title="<?= esc_attr( $field->get_label() ); ?>"
	<?php
	if ( $field->is_required() ) {
		echo 'required';
	}
	if ( $event->state->closed() ) {
		echo 'disabled';
	}
	?>
/>
<label class="wsb-checkbox" for="<?= esc_attr( $field->get_name() ); ?>">
	<?= $field->get_label(); ?>
</label>
