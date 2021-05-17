<?php
/**
 * Upcoming events on the single event page
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<div class="wsb-info" id="upcoming-events">
	<div class="wsb-events__title">
		<?php echo esc_html__( 'sidebar.future', 'wsbintegration' ); ?>
	</div>
	<div data-events-list></div>
</div>
