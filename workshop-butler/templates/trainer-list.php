<?php
/**
 * Template for the single trainer.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?= esc_attr( $theme ); ?>">
    <div class="wsb-content">
        <?php do_action( 'wsb_trainer_list_filters' ); ?>
        <div class="wsb-trainer-list">
            <div class="wsb-tiles" data-trainer-list>
                <?php do_action( 'workshopbutler_before_trainer_list' ); ?>
                <?php do_action( 'wsb_trainer_list_items' ); ?>
                <?php do_action( 'workshopbutler_after_trainer_list' ); ?>
            </div>
            <div class="wsb-no-trainers" data-empty-list>
                <?= esc_html__( 'schedule.noEvents', 'wsbintegration' ); ?>
            </div>
         </div>
         <div class="wsb-copyright"><a href="https://workshopbutler.com/" target="_blank">Powered by Workshop Butler</a></div>
    </div>
</div>
