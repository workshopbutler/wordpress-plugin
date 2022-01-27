<?php
/**
 * Trainer statistics
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


$render_based_on = function ($evaluations) {
    if ( !$evaluations ) return;

    $key = 'trainer.experience.rating.basedOn';
    return sprintf(_n( $key, $key, $evaluations, 'wsbintegration' ), $evaluations);
};
?>

<div class="wsb-trainer-details-facts">
<?php if ( $trainer->get_displayed_public_evaluations() ) { ?>
    <div class="wsb-trainer-details-fact">
    <div>
        <span class="wsb-fact-description"><?php echo esc_html__('trainer.experience.rating.public', 'wsbintegration') ?></span>

        <span class="wsb-fact-description__sub">
        <?php echo esc_html( $render_based_on( $trainer->get_displayed_public_evaluations() )) ?>
        </span>
    </div>

    <div class="wsb-trainer-number-box">
        <span class="wsb-big-number">
        <?php echo esc_html( $trainer->get_displayed_public_rating() ) ?>
        </span>

        <i class="fas fa-star"></i>
    </div>
    </div>
<?php } ?>

<?php if ( $trainer->get_displayed_private_evaluations() ) { ?>
    <div class="wsb-trainer-details-fact">
    <div>
        <span class="wsb-wsb-fact-description"><?php echo esc_html__('trainer.experience.rating.private', 'wsbintegration') ?></span>

        <span class="wsb-fact-description__sub">
        <?php echo esc_html( $render_based_on( $trainer->get_displayed_private_evaluations() )) ?>
        </span>
    </div>

    <div class="wsb-trainer-number-box">
        <span class="wsb-big-number">
        <?php echo esc_html( $trainer->get_displayed_private_rating() ) ?>
        </span>

        <i class="fas fa-star"></i>
    </div>
    </div>
<?php } ?>

<?php if ( $trainer->get_displayed_events_held() ) { ?>
    <div class="wsb-trainer-details-fact">
    <span class="wsb-fact-description"><?php echo esc_html__('trainer.experience.events', 'wsbintegration') ?></span>
    <span class="wsb-big-number wsb-trainer-number-box"><?php echo esc_html(
        $trainer->get_displayed_events_held() ) ?></span>
    </div>
    <?php } ?>

<?php if ( $trainer->get_displayed_years_of_experience() ) { ?>
    <div class="wsb-trainer-details-fact">
    <span class="wsb-fact-description"><?php echo esc_html__('trainer.experience.years', 'wsbintegration') ?></span>
    <span class="wsb-big-number wsb-trainer-number-box"><?php echo esc_html( $trainer->get_displayed_years_of_experience() ) ?></span>
    </div>
<?php } ?>
</div>
