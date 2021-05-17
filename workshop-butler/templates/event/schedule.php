<?php
/**
 * Event's schedule
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use WorkshopButler\Formatter;

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

?>
<div class="wsb-info">
	<div class="wsb-info__title"><?php echo esc_html__( 'event.info.date', 'wsbintegration' ); ?>:</div>
	<?php
	if ( $event->get_schedule()->at_one_day() ) {
		echo esc_html( Formatter::format( $event->get_schedule(), 'full_long' ) . ' ' );
		echo esc_html( Formatter::format( $event->get_schedule(), 'timezone_short' ) );
	} else {
		echo esc_html( Formatter::format( $event->get_schedule(), 'start_long' ) );
		?>
		—<br>
		<?php
		echo esc_html(
			Formatter::format( $event->get_schedule(), 'end_long' ) .
			' ' . Formatter::format( $event->get_schedule(), 'timezone_short' )
		);
	}
	?>
</div>
