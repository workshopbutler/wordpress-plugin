<?php
/**
 * Trainer future events list
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="wsb-workshops" id="upcoming-events">
    <div class="wsb-workshops__title">
    <?= esc_html__('sidebar.future', 'wsbintegration'); ?>
    </div>
    <div data-events-list></div>
</div>
