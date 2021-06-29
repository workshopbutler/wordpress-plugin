<?php
/**
 * Register button for the single event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Single_Event_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use WorkshopButler\Formatter;

?>
<div>
	<?php if ( $event->state->closed() ) { ?>
		<button disabled class="wsb-sidebar__register">
			<?= esc_html( Formatter::format( $event->state ) ); ?>
		</button>
	<?php } else { ?>
		<a href="<?= esc_attr( $event->get_registration_url() ); ?>"
				target="<?= esc_attr( $config->open_registration_page_in() ); ?>"
				class="wsb-sidebar__register">
			<?= esc_html__( 'event.register', 'wsbintegration' ); ?>
		</a>
	<?php } ?>
</div>
