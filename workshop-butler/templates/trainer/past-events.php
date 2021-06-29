<?php
/**
 * Trainer past events list
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="wsb-workshops" id="past-events">
    <div class="wsb-workshops__title">
    <?= esc_html__('sidebar.past', 'wsbintegration'); ?>
    </div>
    <div data-events-list>
    </div>
</div>
