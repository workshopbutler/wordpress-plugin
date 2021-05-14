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
$config = WSB()->dict->get_schedule_config();
$format = 'long' === $config->get_timezone_format() ? 'timezone_long' : 'timezone_short';
?>
<div class="wsb-table__col wsb-table__col-schedule">
	<?php
	echo esc_html( Formatter::format( $event->get_schedule(), 'time' ) );
	if ( $event->get_schedule()->timezone ) {
		if ( $event->get_location()->is_online() && 'online' === $config->get_timezone() || 'all' === $config->get_timezone() ) {
			echo esc_html( '&nbsp;' . Formatter::format( $event->get_schedule(), $format ) );
		}
	}
	?>
</div>
