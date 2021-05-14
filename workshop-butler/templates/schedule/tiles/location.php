<?php
/**
 * Event location on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Formatter;

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

?>
<div class="wsb-tile-info"><?php echo esc_html( Formatter::format( $event->get_location() ) ); ?></div>
