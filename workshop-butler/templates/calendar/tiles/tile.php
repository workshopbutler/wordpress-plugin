<?php
/**
 * Event tile on the event calendar
 *
 * @package WorkshopButler\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
$settings      = WSB()->settings;
$show_featured = $settings->is_highlight_featured() && $event->is_featured() ? 'wsb-featured' : '';

?>
<div class="wsb-tile <?php echo esc_attr( $show_featured ); ?>"
		data-event-obj
		data-event-type="<?php echo esc_attr( $event->get_event_type()->get_id() ); ?>"
		data-event-location="<?php echo esc_attr( $event->get_country_code() ); ?>"
		data-event-language="<?php echo esc_attr( implode( ',', $event->get_spoken_languages() ) ); ?>"
		data-event-trainer="<?php echo esc_attr( implode( ',', $event->get_names_of_trainers() ) ); ?>">
	<?php do_action( 'wsb_calendar_item_tag' ); ?>
	<?php do_action( 'wsb_calendar_item_image' ); ?>
	<?php do_action( 'wsb_calendar_item_schedule' ); ?>
	<?php do_action( 'wsb_calendar_item_location' ); ?>
	<?php do_action( 'wsb_calendar_item_title' ); ?>
	<?php do_action( 'wsb_calendar_item_trainers' ); ?>
	<?php do_action( 'wsb_calendar_item_register' ); ?>
</div>
