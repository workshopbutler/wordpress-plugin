<?php
/**
 * Description of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Single_Event_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $event->description ) {
	?>
	<div class="wsb-description-text"><?php echo wp_kses_post( $event->description ); ?></div>
	<?php
}
