<?php
/**
 * Ticket section of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$section = WSB()->dict->get_form_section();
is_a( $section, 'WorkshopButler\Section' ) || exit();

?>
<section class="wsb-ticket-section">
	<div class="wsb-form__section-title">
		<?php echo esc_html__( strtolower( 'form.section.' . $section->get_id() ), 'wsbintegration' ); ?>
	</div>
	<?php
	foreach ( $section->get_fields() as $field ) {
		WSB()->dict->set_form_field( $field );
		do_action( 'wsb_registration_form_field' );
		WSB()->dict->clear_form_field();
	}
	?>
</section>
