<?php
/**
 * Tag on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

is_a( $event, 'WorkshopButler\Event' ) || exit();

$tag_type = $config->get_tag_type();

?>

<div class="wsb-tile-tags">
	<?php if( $event->state->closed() ) { ?>
		<span class="wsb-tag wsb-sold-out"><?php echo esc_html__( 'event.state.soldOut', 'wsbintegration' ); ?></span>
	<?php }  elseif( $event->is_free && in_array( $tag_type, array( 'all', 'free' ), true ) ) { ?>
		<span class="wsb-free"><?php echo esc_html__( 'event.free', 'wsbintegration' ); ?></span>
	<?php }  elseif( $event->is_featured && in_array( $tag_type, array( 'all', 'featured' ), true ) ) { ?>
		<span class="wsb-tag"><?php echo esc_html__( 'event.featured', 'wsbintegration' ); ?></span>
	<?php } ?>
</div>
