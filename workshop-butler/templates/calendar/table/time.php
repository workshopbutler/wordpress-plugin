<?php
/**
 * Event schedule on the row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

use WorkshopButler\Formatter;

$format = 'long' === $config->get_timezone_format() ? 'timezone_long' : 'timezone_short';
?>
<div class="wsb-table__col wsb-table__col-schedule">
	<?php
	echo esc_html( Formatter::format( $event->schedule, 'time' ) );
	if ( $event->schedule->timezone ) {
		if ( $event->location->is_online() && 'online' === $config->get_timezone() || 'all' === $config->get_timezone() ) {
			echo esc_html( '&nbsp;' . Formatter::format( $event->schedule, $format ) );
		}
	}
	?>
</div>
