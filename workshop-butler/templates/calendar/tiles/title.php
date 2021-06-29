<?php
/**
 * Event title on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Event_Calendar_Config $config
 */
?>

<a href="<?= esc_attr( $event->get_url() ); ?>" class="wsb-tile-title"
	<?php
	if ( $event->is_url_external() ) {
		?>
		target="_blank" <?php } ?>
><?= esc_html( $event->title ); ?></a>&nbsp;
