<?php
/**
 * Trainer past events list
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();
?>

<div class="wsb-workshops" id="past-events">
    <div class="wsb-workshops__title">
    <?php echo esc_html__('sidebar.past', 'wsbintegration'); ?>
    </div>
    <div data-events-list>
    </div>
</div>
