<?php
/**
 * Register button for the single event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use WorkshopButler\Formatter;

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
$config = WSB()->dict->get_single_event_config();
?>
<div>
	<?php if ( $event->get_state()->closed() ) { ?>
		<button disabled class="wsb-sidebar__register">
			<?php echo esc_html( Formatter::format( $event->get_state() ) ); ?>
		</button>
	<?php } else { ?>
		<a href="<?php echo esc_attr( $event->get_registration_url() ); ?>"
				target="<?php echo esc_attr( $config->open_registration_page_in() ); ?>"
				class="wsb-sidebar__register">
			<?php echo esc_html__( 'event.register', 'wsbintegration' ); ?>
		</a>
	<?php } ?>
</div>
