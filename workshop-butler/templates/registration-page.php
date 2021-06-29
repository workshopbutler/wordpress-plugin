<?php
/**
 * Template for the registration form page.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
$settings = WSB()->settings;
$theme    = $settings->get_theme();
?>
<div class="<?= esc_attr( $theme ); ?>">
	<div class="wsb-content">
		<div class="wsb-page wsb-event-page">
			<div class="wsb-body">
				<div class="wsb-toolbar wsb-first">
					<?php do_action( 'wsb_event_schedule' ); ?>
					<?php do_action( 'wsb_event_location' ); ?>
				</div>
				<div class="wsb-description">
					<?php do_action( 'wsb_registration_form' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
