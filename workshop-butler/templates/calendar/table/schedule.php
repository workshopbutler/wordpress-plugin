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

?>
<div class="wsb-table__col wsb-table__col-schedule wsb-datetime">
	<?php
	if ( $event->schedule->at_one_day() ) {
		echo esc_html( Formatter::format( $event->schedule, 'full_long' ) );
		if ( $event->location->is_online() && $event->schedule->timezone ) {
			echo esc_html( '&nbsp;' . Formatter::format( $event->schedule, 'timezone_short' ) );
		}
	} else {
		echo esc_html( Formatter::format( $event->schedule, 'full_short' ) );
	}
	?>
</div>
