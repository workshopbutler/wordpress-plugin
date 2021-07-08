<?php
/**
 * Country field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Field $field
 */

require_once WSB_ABSPATH . '/public/includes/view/class-countries.php';

use WorkshopButler\View\Countries;

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
	<option value="" selected disabled><?= esc_html__( 'form.country', 'wsbintegration' ); ?></option>
	<?php
	foreach ( Countries::get() as $code => $name ) {
		?>
		<option value="<?= esc_attr( $code ); ?>"><?= esc_attr( $name ); ?></option>
	<?php } ?>
</select>
