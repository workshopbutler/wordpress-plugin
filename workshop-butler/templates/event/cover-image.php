<?php
/**
 * Event cover image
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Single_Event_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


$url = $event->cover_image->url;

if ( !$url ) {
	return;
}
?>
<img src="<?php echo esc_attr( $url ); ?>" alt="<?php echo esc_attr( $event->title ); ?>" class="wsb-cover-image"/>
