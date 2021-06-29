<?php
/**
 * Event's schedule
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Single_Event_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use WorkshopButler\Formatter;

?>
<div class="wsb-info">
	<div class="wsb-info__title"><?= esc_html__( 'event.info.date', 'wsbintegration' ); ?>:</div>
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
</div>
