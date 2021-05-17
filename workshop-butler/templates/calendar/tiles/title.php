<?php
/**
 * Event title on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
$event_type = $event->get_event_type();

?>

<a href="<?php echo esc_attr( $event->get_url() ); ?>" class="wsb-tile-title"
	<?php
	if ( $event->is_url_external() ) {
		?>
		target="_blank" <?php } ?>
><?php echo esc_html( $event->title ); ?></a>&nbsp;
