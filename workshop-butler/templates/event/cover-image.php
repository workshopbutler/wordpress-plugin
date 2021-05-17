<?php
/**
 * Event cover image
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

$url = $event->get_cover_image()->get_url();

if ( $url ) {
	?>
	<img src="<?php echo esc_attr( $url ); ?>"
			alt="<?php echo esc_attr( $event->get_title() ); ?>" width="100%"/>
	<?php
}
