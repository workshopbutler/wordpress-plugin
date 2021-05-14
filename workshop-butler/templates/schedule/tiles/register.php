<?php
/**
 * Register button on the tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

$url = WSB()->dict->get_schedule_config()->is_skip_event_page() ? $event->registration_page->get_url() : $event->get_url();
?>
<div class="wsb-tile-button">
	<?php if ( $event->state->closed() ) { ?>
		<button disabled class="wsb-btn wsb-btn-primary wsb-tile-btn">
			<?php echo esc_html__( 'schedule.event.soldOut', 'wsbintegration' ); ?>
		</button>
	<?php } else { ?>
		<a href="<?php echo esc_attr( $url ); ?>"
				class="wsb-btn wsb-btn-primary wsb-tile-btn">
			<?php echo esc_html__( 'schedule.event.register', 'wsbintegration' ); ?>
		</a>
	<?php } ?>
</div>
