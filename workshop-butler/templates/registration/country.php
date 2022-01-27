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
		name="<?php echo esc_attr( $field->get_name() ); ?>"
		title="<?php echo esc_attr( $field->get_label() ); ?>"
	<?php
	if ( $field->is_required() ) {
		echo 'required';
	}
	if ( $event->state->closed() ) {
		echo 'disabled';
	}
	?>
>
	<option value="" selected disabled><?php echo esc_html__( 'form.country', 'wsbintegration' ); ?></option>
	<?php
	foreach ( Countries::get() as $code => $name ) {
		?>
		<option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_attr( $name ); ?></option>
	<?php } ?>
</select>
