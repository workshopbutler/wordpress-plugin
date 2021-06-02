<?php
/**
 * Trainer future events list
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();
?>

<div class="wsb-workshops" id="upcoming-events">
    <div class="wsb-workshops__title">
    <?php echo esc_html__('sidebar.future', 'wsbintegration'); ?>
    </div>
    <div data-events-list></div>
</div>
