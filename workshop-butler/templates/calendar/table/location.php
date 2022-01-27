<?php
/**
 * Event location on the event row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

use WorkshopButler\Formatter;

?>
<div class="wsb-table__col wsb-table__col-location"><?php echo esc_html( Formatter::format( $event->location ) ); ?></div>
