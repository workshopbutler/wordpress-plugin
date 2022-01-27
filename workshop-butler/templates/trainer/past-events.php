<?php
/**
 * Trainer past events list
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="wsb-events" id="past-events">
    <div class="wsb-events__title">
    <?php echo esc_html__('sidebar.past', 'wsbintegration'); ?>
    </div>
    <div data-events-list>
    </div>
</div>
