<?php
/**
 * Event image on the tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Event_Calendar_Config $config
 */

$thumbnail_url = $event->cover_image->get_thumbnail_url();

if ( $thumbnail_url ) {
	?>
	<a href="<?= esc_attr( $event->get_url() ); ?>">
		<img src="<?= esc_attr( $thumbnail_url ); ?>"
				alt="<?= esc_attr( $event->title ); ?>" width="300" height="200"/>
	</a>

	<?php
}
