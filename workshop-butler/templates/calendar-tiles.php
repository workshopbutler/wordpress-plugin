<?php
/**
 * The Template for displaying calendar tiles.
 *
 * This template can be overridden by copying it to yourtheme/workshop-butler/calendar-tiles.php.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?php echo esc_attr( $theme ); ?>">
	<div class="wsb-content">
		<?php do_action( 'wsb_filters' ); ?>
		<div class="wsb-schedule">
			<div class="wsb-tiles" data-event-list>
				<?php do_action( 'workshopbutler_before_schedule' ); ?>
				<?php do_action( 'wsb_calendar' ); ?>
				<?php do_action( 'workshopbutler_after_schedule' ); ?>
			</div>
			<div class="wsb-no-events">
				<?php echo esc_html__( 'schedule.noEvents', 'wsbintegration' ); ?>
			</div>
		</div>
		<div class="wsb-copyright"><a href="https://workshopbutler.com/" target="_blank">Powered by Workshop Butler</a></div>
	</div>
</div>
