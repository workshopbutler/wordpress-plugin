<?php
/**
 * Event dates on the row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Event_Calendar_Config $config
 */

use WorkshopButler\Formatter;

?>
<div class="wsb-table__col wsb-table__col-schedule">
	<?php
	echo esc_html( Formatter::format( $event->schedule, 'full_short' ) );
	?>
</div>
