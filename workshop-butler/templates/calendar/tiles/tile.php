<?php
/**
 * Event tile on the event calendar
 *
 * @package WorkshopButler\Templates
 * @version 3.0.0
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$show_featured = $config->is_highlight_featured() && $event->is_featured ? 'wsb-featured' : '';

?>
<a href="<?= esc_attr( $event->get_url() ); ?>"
	class="wsb-tile <?= esc_attr( $show_featured ); ?>"
	data-event-obj
	data-event-type="<?= esc_attr( $event->type->get_id() ); ?>"
	data-event-location="<?= esc_attr( $event->get_country_code() ); ?>"
	data-event-language="<?= esc_attr( implode( ',', $event->get_spoken_languages() ) ); ?>"
	data-event-trainer="<?= esc_attr( implode( ',', $event->get_names_of_trainers() ) ); ?>">

	<?php if( $event->cover_image->thumbnail ) { ?>
		<img class="wsb-tile-image" alt="<?= esc_attr( $event->title ); ?>"
	  		src="<?= esc_attr( $event->cover_image->thumbnail ); ?>" />
	<?php } ?>

    <div class="wsb-tile-header with-stub">
		<?php do_action( 'wsb_calendar_item_tag' ); ?>
		<div class="wsb-tile-title">
			<?= esc_html( $event->title ); ?>
		</div>
    </div>

	<?php do_action( 'wsb_calendar_item_schedule' ); ?>
	<?php do_action( 'wsb_calendar_item_trainers' ); ?>
</a>
