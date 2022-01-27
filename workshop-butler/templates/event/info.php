<?php
/**
 * Event's schedule
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Single_Event_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use WorkshopButler\Formatter;

?>
<div class="wsb-info">
	<span class="flag-icon flag-icon-<?php echo esc_attr( strtolower( $event->location->country_code ) ); ?> wsb-flag"></span>
	<?php echo esc_html( Formatter::format( $event->location ) ); ?> <br/>
	<?php
	if ( $event->schedule->at_one_day() ) {
		echo esc_html( Formatter::format( $event->schedule, 'full_long' ) . ' ' );
		echo esc_html( Formatter::format( $event->schedule, 'timezone_short' ) );
	} else {
		echo esc_html( Formatter::format( $event->schedule, 'start_long' ) );
		?>
		—<br>
		<?php
		echo esc_html(
			Formatter::format( $event->schedule, 'end_long' ) .
			' ' . Formatter::format( $event->schedule, 'timezone_short' )
		);
	}
	?>
	<div class="wsb-info__footer"><?php echo esc_html( Formatter::format( $event->language ) ); ?></div>
</div>
