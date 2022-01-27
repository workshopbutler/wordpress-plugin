<?php
/**
 * Label of the registration form's field
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Field $field
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * This is a quite dirty hack. In 'converter.php' we replace 'street_1' and 'street_2' with
 * 'street_first/street_second' to prevent i18next-conv transform them as plurals.
 *
 * As a result, our translation files contain several keys not in line with Workshop Butler backend.
 * To make the translation work, we need to convert the keys again.
 */
$label = '';
if ( $field->is_custom() ) {
	$label = $field->get_label();
} else {

	$replaced_street_1 = str_replace( 'street_1', 'street_first', 'form.field.' . $field->get_name() );
	$replaced_street_2 = str_replace( 'street_2', 'street_second', $replaced_street_1 );
	$label             = esc_html__( $replaced_street_2, 'wsbintegration' );
}
if ( 'checkbox' !== $field->get_type() ) {
	if ( 'promo_code' !== $field->get_name() ) { ?>
		<label for="<?php echo esc_attr( $field->get_name() ); ?>" class="wsb-label">
			<?php
			echo esc_html( $label );
			if ( $field->is_required() ) {
				?>
				<span class="wsb-form__required">*</span>
			<?php } ?>
		</label>
		<?php
	}
} else {
	?>
	<label for="<?php echo esc_attr( $field->get_name() ); ?>" class="wsb-label"></label>
	<?php
}
