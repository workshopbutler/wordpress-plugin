<?php
/**
 * Event row on the event calendar
 *
 * @package WorkshopButler\Templates
 * @version 3.0.0
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

use WorkshopButler\Config\Calendar_Item_Elements;

$show_featured = $config->is_highlight_featured() && $event->is_featured ? 'wsb-featured' : '';
$row_level_tag = true;

if ( is_a( $event, 'WP_Error' ) || ! $event ) {
	exit();
}
?>
<div class="wsb-table__row <?php echo esc_attr( $show_featured ); ?>"
		data-event-obj
		data-event-type="<?php echo esc_attr( $event->type->get_id() ); ?>"
		data-event-location="<?php echo esc_attr( $event->get_country_code() ); ?>"
		data-event-language="<?php echo esc_attr( implode( ',', $event->get_spoken_languages() ) ); ?>"
		data-event-trainer="<?php echo esc_attr( implode( ',', $event->get_names_of_trainers() ) ); ?>">
	<?php do_action( 'wsb_calendar_item_tag', $row_level_tag ); ?>
	<?php
	foreach ( $config->get_elements() as $element ) {
		if ( Calendar_Item_Elements::is_valid( $element ) ) {
			do_action( 'wsb_calendar_item_' . $element );
		}
	}
	?>
</div>
