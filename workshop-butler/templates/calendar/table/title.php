<?php
/**
 * Event title on the event row
 *
 * @package WorkshopButler\Templates
 * @version 3.0.0
 */

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

$event      = WSB()->dict->get_event();
$event_type = $event->get_event_type();
$with_badge = $event_type->has_badge() ? 'with-badge' : '';
?>

<div class="wsb-table__col wsb-table__col-title <?php echo esc_attr( $with_badge ); ?>">
	<?php if ( $event_type->has_badge() ) { ?>
		<img class="wsb-table__img" src="<?php echo esc_attr( $event_type->get_badge_url() ); ?>"
				alt="<?php echo esc_attr( $event_type->get_name() ); ?>"/>
	<?php } ?>
	<a href="<?php echo esc_attr( $event->get_url() ); ?>" class="wsb-table__link"
		<?php
		if ( $event->is_url_external() ) {
			?>
			target="_blank" <?php } ?>
	><?php echo esc_html( $event->title ); ?></a>&nbsp;<?php do_action( 'wsb_calendar_item_tag' ); ?>
</div>
