<?php
/**
 * Tag on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 * @global boolean $mobile
 */

$tag_type       = $config->get_tag_type();
$mobile_support = $mobile ? 'wsb-mobile' : '';

is_a( $event, 'WorkshopButler\Event' ) || exit();

if ( $event->is_free && in_array( $tag_type, array( 'all', 'free' ), true ) ) {
	?>
	<span class="<?php echo esc_attr( $mobile_support ); ?> wsb-free">
		<?php echo esc_html__( 'schedule.event.free', 'wsbintegration' ); ?>
	</span>
	<?php
} elseif ( $event->is_featured && in_array( $tag_type, array( 'all', 'featured' ), true ) ) {
	?>
<span class="<?php echo esc_attr( $mobile_support ); ?> wsb-tag">
	<?php echo esc_html__( 'event.featured', 'wsbintegration' ); ?>
	</span><?php
}
?>
