<?php
/**
 * Select field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$field = WSB()->dict->get_form_field();
is_a( $field, 'WorkshopButler\Select' ) || exit();
$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

?>
<select data-control
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
>
<?php
foreach ( $field->get_options() as $option ) {
	?>
		<option value="<?php echo esc_attr( $option->get_value() ); ?>"><?php echo esc_html( $option->get_label() ); ?>
		</option>
	<?php } ?>
</select>
