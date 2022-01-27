<?php
/**
 * Textarea field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Field $field
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<textarea name="<?php echo esc_attr( $field->get_name() ); ?>" data-control
		title="<?php echo esc_attr( $field->get_label() ); ?>"
	<?php
	if ( $field->is_required() ) {
		echo 'required';
	}
	if ( $event->state->closed() ) {
		echo 'disabled';
	}
	?>
	style="height : 90px ;" cols="30" rows="10">
</textarea>
