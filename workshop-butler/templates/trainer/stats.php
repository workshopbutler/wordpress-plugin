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
<?php if ( $trainer->stats->total->public_stats->evaluations ) { ?>
    <div class="wsb-trainer-details-fact">
    <div>
        <span class="wsb-fact-description"><?= esc_html__('trainer.experience.rating.public', 'wsbintegration') ?></span>

        <span class="wsb-fact-description__sub">
        <?= esc_html( $render_based_on( $trainer->stats->total->public_stats->evaluations )) ?>
        </span>
    </div>

    <div class="wsb-trainer-number-box">
        <span class="wsb-big-number">
        <?= esc_html( $trainer->stats->total->public_stats->get_rounded_rating() ) ?>
        </span>

        <i class="fas fa-star"></i>
    </div>
    </div>
<?php } ?>

<?php if ( $trainer->stats->total->private_stats->evaluations ) { ?>
    <div class="wsb-trainer-details-fact">
    <div>
        <span class="wsb-wsb-fact-description"><?= esc_html__('trainer.experience.rating.private', 'wsbintegration') ?></span>

        <span class="wsb-fact-description__sub">
        <?= esc_html( $render_based_on( $trainer->stats->total->private_stats->evaluations )) ?>
        </span>
    </div>

    <div class="wsb-trainer-number-box">
        <span class="wsb-big-number">
        <?= esc_html( $trainer->stats->total->private_stats->get_rounded_rating() ) ?>
        </span>

        <i class="fas fa-star"></i>
    </div>
    </div>
<?php } ?>

<?php if ( $trainer->number_of_events ) { ?>
    <div class="wsb-trainer-details-fact">
    <span class="wsb-fact-description"><?= esc_html__('trainer.experience.events', 'wsbintegration') ?></span>
    <span class="wsb-big-number wsb-trainer-number-box"><?= esc_html( $trainer->number_of_events ) ?></span>
    </div>
    <?php } ?>

<?php if ( $trainer->years_of_experience ) { ?>
    <div class="wsb-trainer-details-fact">
    <span class="wsb-fact-description"><?= esc_html__('trainer.experience.years', 'wsbintegration') ?></span>
    <span class="wsb-big-number wsb-trainer-number-box"><?= esc_html( $trainer->years_of_experience ) ?></span>
    </div>
<?php } ?>
</div>
