<?php
/**
 * Register button on the event row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Event_Calendar_Config $config
 */

$url = $config->is_skip_event_page() ? $event->registration_page->get_url() : $event->get_url();
?>
<div class="wsb-table__col wsb-table__col-register">
	<?php if ( $event->state->closed() ) { ?>
		<a disabled class="wsb-table__btn"><?= esc_html__( 'schedule.event.soldOut', 'wsbintegration' ); ?></a>
	<?php } else { ?>
		<a href="<?= esc_attr( $url ); ?>"
				class="wsb-table__btn">
			<?= esc_html__( 'schedule.event.register', 'wsbintegration' ); ?>
		</a>
	<?php } ?>
</div>
