<?php
/**
 * Event schedule on the tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

use WorkshopButler\Formatter;

?>
<div class="wsb-date-place">
	<div class="wsb-tile-info wsb-tile-location">
		<span class="flag-icon flag-icon-<?php echo esc_attr( strtolower( $event->location->country_code ) ); ?> wsb-flag"></span>
		<?php echo esc_html( Formatter::format( $event->location ) ); ?>
	</div>

	<div class="wsb-tile-info wsb-tile-datetime">
	<?php if ( $event->schedule->at_one_day() ) {
		echo esc_html( Formatter::format( $event->schedule, 'full_long' ) );
		if ( $event->location->is_online() && $event->schedule->timezone ) {
			echo esc_html( Formatter::format( $event->schedule, 'timezone_short' ) );
		}
	} else {
		echo esc_html( Formatter::format( $event->schedule, 'full_short' ) );
	} ?>
	</div>
</div>
