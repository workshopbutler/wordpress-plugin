<?php
/**
 * Event location on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Event_Calendar_Config $config
 */

use WorkshopButler\Formatter;

?>
<div class="wsb-tile-info"><?= esc_html( Formatter::format( $event->location ) ); ?></div>
