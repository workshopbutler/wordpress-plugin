<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WorkshopButler\Templates
 * @version     3.0.0
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?= esc_attr( $theme ); ?>">
	<div class="wsb-content">
		<?php do_action( 'wsb_filters' ); ?>
		<div class="wsb-schedule">
			<div class="wsb-table" data-event-list>
				<?php do_action( 'workshopbutler_before_schedule' ); ?>
				<?php do_action( 'wsb_calendar' ); ?>
				<?php do_action( 'workshopbutler_after_schedule' ); ?>
			</div>
			<div class="wsb-no-events">
				<?= esc_html__( 'schedule.noEvents', 'wsbintegration' ); ?>
			</div>
		</div>
		<div class="wsb-copyright"><a href="https://workshopbutler.com/" target="_blank">Powered by Workshop Butler</a></div>
	</div>
</div>
