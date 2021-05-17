<?php
/**
 * Event language on the tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Formatter;

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
?>
<div class="wsb-tile-info wsb-schedule-language">
	<?php
	echo esc_html( Formatter::format( $event->get_language(), 'short' ) );
	?>
</div>
