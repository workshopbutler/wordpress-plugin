<?php
/**
 * Checkbox field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$field = WSB()->dict->get_form_field();
is_a( $field, 'WorkshopButler\Field' ) || exit();
$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

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
