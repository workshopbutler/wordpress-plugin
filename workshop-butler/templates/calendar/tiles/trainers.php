<?php
/**
 * Event trainers on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

?>
<div class="wsb-tile-content">
	<?php foreach ( $event->trainers as $trainer ) { ?>
		<div class="wsb-trainer">
			<img class="wsb-photo" src="<?php echo esc_attr( $trainer->get_photo_or_default() ); ?>"
					alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>"/>
			<?php if ( $config->is_show_trainer_name() ) {
				echo esc_html( $trainer->get_full_name() );
			} ?>
		</div>
	<?php } ?>
</div>
