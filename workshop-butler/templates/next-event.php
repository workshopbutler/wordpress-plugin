<?php
/**
 * The Template for displaying Next Event element
 *
 * This template can be overridden by copying it to yourtheme/workshop-butler/next-event.php.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
$no_event_title = $config->get_no_event_title() ? $config->get_no_event_title() : esc_html__( 'nextEvent.notAvailable', 'wsbintegration' );

?>

<div class="<?= esc_attr( $theme ); ?>">
	<div class="wsb-content wsb-next-event">

	<?php
	if ( is_a( $event, 'WorkshopButler\Event' ) ) {
		$url          = $config->is_registration() ? $event->get_registration_url() : $event->get_url();
		$button_title = $config->get_title() ? $config->get_title() : esc_html__( 'nextEvent.register', 'wsbintegration' );
		?>

		<a href="<?= esc_attr( $url ); ?>"
				target="<?= esc_attr( $config->open_page_in() ); ?>" class="wsb-next-element-button">
			<?= esc_html( $button_title ); ?>
		</a>
	<?php } else { ?>
		<span class="wsb-next-element-no-event">
			<?= esc_html( $no_event_title ); ?>
		</span>
		<?php
	}
	?>

	</div>
</div>
