<?php
/**
 * Description of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Single_Event_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $event->description ) {
	?>
	<div class="wb-desc"><?= esc_html($event->description); ?></div>
	<?php
}
