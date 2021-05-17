<?php
/**
 * Description of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

if ( $event->get_description() ) {
	?>
	<div class="wb-desc"><?php echo $event->get_description(); ?></div>
	<?php
}
