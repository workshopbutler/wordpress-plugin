<?php
/**
 * Event dates on the tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Event $event
 * @global Event_Calendar_Config $config
 */

use WorkshopButler\Formatter;

?>
<div class="wsb-tile-info">
	<?php
	echo esc_html( Formatter::format( $event->schedule, 'full_short' ) );
	?>
</div>
