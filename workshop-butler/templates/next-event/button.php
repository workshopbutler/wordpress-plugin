<?php
/**
 * Button for Next Event element
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

$event          = WSB()->dict->get_event();
$config         = WSB()->dict->get_next_event_config();
$no_event_title = $config->get_no_event_title() ? $config->get_no_event_title() : esc_html__( 'nextEvent.notAvailable', 'wsbintegration' );

if ( is_a( $event, 'WorkshopButler\Event' ) ) {
	$url          = $config->is_registration() ? $event->get_registration_url() : $event->get_url();
	$button_title = $config->get_title() ? $config->get_title() : esc_html__( 'nextEvent.register', 'wsbintegration' );
	?>

	<a href="<?php echo esc_attr( $url ); ?>"
			target="<?php echo esc_attr( $config->open_page_in() ); ?>" class="wsb-next-element-button">
		<?php echo esc_html( $button_title ); ?>
	</a>
<?php } else { ?>
	<span class="wsb-next-element-no-event">
		<?php echo esc_html( $no_event_title ); ?>
	</span>
	<?php
}

