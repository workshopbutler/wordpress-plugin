<?php
/**
 * Event location on the single event page
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Single_Event_Config $config
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use WorkshopButler\Formatter;

?>
<div class="wsb-info">
	<div class="wsb-info__title"><?= esc_html__( 'event.info.loc', 'wsbintegration' ); ?>:</div>
	<?= esc_html( Formatter::format( $event->location ) ); ?>
	<div class="wsb-info__footer"><?= esc_html( Formatter::format( $event->language ) ); ?></div>
</div>
