<?php

/**
 * The Template for displaying single trainer.
 *
 * This template can be overridden by copying it to yourtheme/workshop-butler/single-trainer.php.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?php echo esc_attr( $theme ); ?>">
    <div class="wsb-content">
        <div class="wsb-page">
            <div class="wsb-body wsb-trainer-profile">
                <div class="wsb-description">
                    <div class="trainer-profile-header">
                        <?php do_action( 'wsb_trainer_photo' ); ?>
                        <div class="wsb-trainer-title">
                            <h2 class="wsb-trainer-name"><?php echo esc_html( $trainer->get_full_name() ); ?></h2>
                            <?php do_action( 'wsb_trainer_country' ); ?>
                        </div>
                        <div class="wsb-trainer-buttons">
                            <?php do_action( 'wsb_trainer_email' ); ?>
                            <?php do_action( 'wsb_trainer_social_links' ); ?>
                        </div>
                    </div>
                    <?php do_action( 'wsb_trainer_stats' ); ?>
                    <?php do_action( 'wsb_trainer_badges' ); ?>
                    <?php do_action( 'wsb_trainer_testimonials' ); ?>
                    <?php do_action( 'wsb_trainer_bio' ); ?>
                </div>
                <div class="wsb-aside">
                    <?php do_action( 'wsb_trainer_badges' ); ?>
                    <?php do_action( 'wsb_trainer_stats' ); ?>
                    <div class="wsb-trainer-events">
                        <?php do_action( 'wsb_trainer_future_events' ); ?>
                        <?php do_action( 'wsb_trainer_past_events' ); ?>
                    </div>
                    <div class="wsb-copyright"><a href="https://workshopbutler.com/" target="_blank">Powered by Workshop Butler</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
