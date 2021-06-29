<?php
/**
 * Tag on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Event_Calendar_Config $config
 */

$tag_type = $config->get_tag_type();

is_a( $event, 'WorkshopButler\Event' ) || exit();

if ( $event->is_free && in_array( $tag_type, array( 'all', 'free' ), true ) ) {
	?>
	<span class="wsb-free">
		<?= esc_html__( 'schedule.event.free', 'wsbintegration' ); ?>
	</span>
	<?php
} elseif ( $event->is_featured && in_array( $tag_type, array( 'all', 'featured' ), true ) ) {
	?>
	<span class="wsb-tag">
	<?= esc_html__( 'event.featured', 'wsbintegration' ); ?>
	</span><?php
}
?>
