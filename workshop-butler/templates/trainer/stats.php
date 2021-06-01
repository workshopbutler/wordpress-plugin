<?php
/**
 * Trainer statistics
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();

function wsb_pt( $key, $number ) {
    return sprintf(
        _n( $key, $key, $number, 'wsbintegration' ),
        $number
    );
}

function render_rating($rating, $evaluations, $description) {
    if ( !$evaluations ) return;

    $evaluations = wsb_pt('trainer.experience.rating.basedOn', $evaluations);

    echo <<<EOD
<div class="wsb-trainer-details-fact">
    <span class="wsb-big-number">$rating</span>&nbsp;<span
            class="wsb-small-number">/&nbsp;10</span>
    <span class="wsb-descr">$description</span>
    <span class="wsb-descr__sub">
      $evaluations
    </span>
</div>
EOD;
}

function render_number($number, $description) {
    if ( !$number ) return;

    echo <<<EOD
<div class="wsb-trainer-details-fact">
    <span class="wsb-big-number">$number</span>
    <span class="wsb-descr">$description</span>
</div>
EOD;
}

render_number($trainer->years_of_experience, esc_html__('trainer.experience.years', 'wsbintegration'));
render_number($trainer->number_of_events, esc_html__('trainer.experience.events', 'wsbintegration'));
render_rating(
    $trainer->stats->total->public_stats->rating,
    $trainer->stats->total->public_stats->evaluations,
    esc_html__('trainer.experience.rating.public', 'wsbintegration')
);
render_rating(
    $trainer->stats->total->private_stats->rating,
    $trainer->stats->total->private_stats->evaluations,
    esc_html__('trainer.experience.rating.private', 'wsbintegration')
);

?>
