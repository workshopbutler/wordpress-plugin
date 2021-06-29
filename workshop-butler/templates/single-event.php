<?php
/**
 * Template for the single event.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?= esc_attr( $theme ); ?>">
	<div class="wsb-content">
		<div class="wsb-page wsb-event-page">
			<div class="wsb-body">
				<div class="wsb-toolbar wsb-first">
					<?php do_action( 'wsb_event_register_button' ); ?>
					<?php do_action( 'wsb_event_schedule' ); ?>
					<?php do_action( 'wsb_event_location' ); ?>
					<?php do_action( 'wsb_event_tickets' ); ?>
					<?php do_action( 'wsb_event_trainers' ); ?>
				</div>
				<div class="wsb-description">
					<?php do_action( 'wsb_event_cover_image' ); ?>
					<?php do_action( 'wsb_event_description' ); ?>
				</div>
				<div class="wsb-toolbar wsb-second">
					<?php do_action( 'wsb_event_social_links' ); ?>
					<?php do_action( 'wsb_event_events' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
