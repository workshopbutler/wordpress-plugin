<?php
/**
 * Select field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Field $field
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<select data-control
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
>
<?php
foreach ( $field->get_options() as $option ) {
	?>
		<option value="<?= esc_attr( $option->get_value() ); ?>"><?= esc_html( $option->get_label() ); ?>
		</option>
	<?php } ?>
</select>
