<?php
/**
 * Template for the single event.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Single_Event_Config $config
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?= esc_attr( $theme ); ?>">
	<div class="wsb-content">
		<div class="wsb-page wsb-event-page">
			<div class="wsb-header">
				<?php if ( 1 || $event->confirmed ) { ?><div style="color:#819D44;">Confirmed <i class="fas fa-check"></i></div><?php } ?>
				<h1><?= esc_html( $event->title ) ?></h1>
			</div>
			<div class="wsb-body">
				<div class="wsb-description">
					<?php do_action( 'wsb_event_cover_image' ); ?>
					<?php do_action( 'wsb_event_info' ); ?>
					<?php do_action( 'wsb_event_register_button' ); ?>
					<?php do_action( 'wsb_event_description' ); ?>
				</div>

				<div class="wsb-aside">
					<?php do_action( 'wsb_event_info' ); ?>
					<?php do_action( 'wsb_event_register_button' ); ?>
					<?php do_action( 'wsb_event_tickets' ); ?>
					<?php do_action( 'wsb_event_trainers' ); ?>
					<?php do_action( 'wsb_event_events' ); ?>
					<?php do_action( 'wsb_event_social_links' ); ?>
					<div class="wsb-copyright"><a href="https://workshopbutler.com/" target="_blank">Powered by Workshop Butler</a></div>
				</div>
			</div>
		</div>
	</div>
</div>
