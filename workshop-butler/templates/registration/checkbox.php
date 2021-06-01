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
		name="<?php echo esc_attr( $field->get_name() ); ?>"
		title="<?php echo esc_attr( $field->get_label() ); ?>"
	<?php
	if ( $field->is_required() ) {
		echo 'required';
	}
	if ( $event->get_state()->closed() ) {
		echo 'disabled';
	}
	?>
/>
<label class="wsb-checkbox" for="<?php echo esc_attr( $field->get_name() ); ?>">
	<?php echo $field->get_label(); ?>
</label>
