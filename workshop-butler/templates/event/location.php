<?php
/**
 * Event location on the single event page
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use WorkshopButler\Formatter;

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

?>
<div class="wsb-info">
	<div class="wsb-info__title"><?php echo esc_html__( 'event.info.loc', 'wsbintegration' ); ?>:</div>
	<?php echo esc_html( Formatter::format( $event->get_location() ) ); ?>
	<div class="wsb-info__footer"><?php echo esc_html( Formatter::format( $event->get_language() ) ); ?></div>
</div>
