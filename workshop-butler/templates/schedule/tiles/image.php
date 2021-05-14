<?php
/**
 * Event image on the tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

$thumbnail_url = $event->get_cover_image()->get_thumbnail_url();

if ( $thumbnail_url ) {
	?>
	<a href="<?php echo esc_attr( $event->get_url() ); ?>">
		<img src="<?php echo esc_attr( $thumbnail_url ); ?>"
				alt="<?php echo esc_attr( $event->get_title() ); ?>" width="300" height="200"/>
	</a>

	<?php
}
