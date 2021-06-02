<?php
/**
 * The Template for displaying Next Event element
 *
 * This template can be overridden by copying it to yourtheme/workshopbutler/next-event.php.
 *
 * HOWEVER, on occasion Workshop Butler  will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package     WorkshopButler\Templates
 * @version     3.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?php echo esc_attr( $theme ); ?>">
	<div class="wsb-content wsb-next-event">
		<?php do_action( 'workshopbutler_next_event_button' ); ?>
	</div>
</div>

