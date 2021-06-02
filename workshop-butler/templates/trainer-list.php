<?php
/**
 * Template for the single trainer.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$settings = WSB()->settings;
$theme    = $settings->get_theme();
?>
<div class="<?php echo esc_attr( $theme ); ?>">
    <div class="wsb-content">
        <?php do_action( 'wsb_trainer_list_filters' ); ?>
        <div class="wsb-trainer-list">
            <div class="wsb-tiles" data-trainer-list>
                <?php do_action( 'workshopbutler_before_trainer_list' ); ?>
                <?php do_action( 'wsb_trainer_list_items' ); ?>
                <?php do_action( 'workshopbutler_after_trainer_list' ); ?>
            </div>
            <div class="wsb-no-trainers">
                <?= esc_html__( 'schedule.noEvents', 'wsbintegration' ); ?>
            </div>
         </div>

    </div>
</div>
