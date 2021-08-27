<?php
/**
 * Event language on the row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

use WorkshopButler\Formatter;

?>
<div class="wsb-table__col wsb-table__col-schedule wsb-schedule-language">
	<?php
	echo esc_html( Formatter::format( $event->language, 'short' ) );
	?>
</div>
