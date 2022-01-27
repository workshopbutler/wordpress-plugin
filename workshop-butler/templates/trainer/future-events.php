<?php
/**
 * Trainer future events list
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="wsb-events" id="upcoming-events">
    <div class="wsb-events__title">
    <?php echo esc_html__('sidebar.future', 'wsbintegration'); ?>
    </div>
    <div data-events-list></div>
</div>
