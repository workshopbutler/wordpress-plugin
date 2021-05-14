<?php
/**
 * Event schedule on the row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Formatter;

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
?>
<div class="wsb-table__col wsb-table__col-schedule wsb-datetime">
	<?php
	if ( $event->get_schedule()->at_one_day() ) {
		echo esc_html( Formatter::format( $event->get_schedule(), 'full_long' ) );
		if ( $event->get_location()->is_online() && $event->schedule->timezone ) {
			echo esc_html( Formatter::format( $event->get_schedule(), 'timezone_short' ) );
		}
	} else {
		echo esc_html( Formatter::format( $event->get_schedule(), 'full_short' ) );
	}
	?>
</div>
