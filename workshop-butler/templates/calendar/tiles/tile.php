<?php
/**
 * Event tile on the event calendar
 *
 * @package WorkshopButler\Templates
 * @version 3.0.0
 * @global Event $event
 * @global Event_Calendar_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$show_featured = $config->is_highlight_featured() && $event->is_featured ? 'wsb-featured' : '';

?>
<div class="wsb-tile <?= esc_attr( $show_featured ); ?>"
		data-event-obj
		data-event-type="<?= esc_attr( $event->type->get_id() ); ?>"
		data-event-location="<?= esc_attr( $event->get_country_code() ); ?>"
		data-event-language="<?= esc_attr( implode( ',', $event->get_spoken_languages() ) ); ?>"
		data-event-trainer="<?= esc_attr( implode( ',', $event->get_names_of_trainers() ) ); ?>">
	<?php do_action( 'wsb_calendar_item_tag' ); ?>
	<?php do_action( 'wsb_calendar_item_image' ); ?>
	<?php do_action( 'wsb_calendar_item_schedule' ); ?>
	<?php do_action( 'wsb_calendar_item_location' ); ?>
	<?php do_action( 'wsb_calendar_item_title' ); ?>
	<?php do_action( 'wsb_calendar_item_trainers' ); ?>
	<?php do_action( 'wsb_calendar_item_register' ); ?>
</div>
