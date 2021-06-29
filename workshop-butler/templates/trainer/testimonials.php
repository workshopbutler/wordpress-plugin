<?php
/**
 * Trainer testimonials
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<h3><?= esc_html__('trainer.feedback', 'wsbintegration'); ?></h3>
<div>
    <?php foreach ( $trainer->testimonials as $testimonial ) { ?>
        <div class="wsb-testimonial">

            <span class="wsb-cite">
                <strong><?= esc_html( $testimonial->attendee ); ?></strong>
                <?php  if ( $testimonial->company ) { echo ", ".esc_html( $testimonial->company ); } ?>
            </span>
            <?php  if ( $testimonial->rating ) {  ?>
            <div class="wsb-rating">
                <?php foreach (range(1, 5) as $index ) {
                    if ( $index * 2 <= $testimonial->rating ) { ?>
                        <i class="fas fa-star"></i>
                    <?php } elseif ( $index * 2 > $testimonial->rating + 1 ) { ?>
                        <i class="far fa-star"></i>
                    <?php } else { ?>
                        <div class="fa-stack">
                            <i class="fas fa-star-half fa-stack-1x"></i>
                            <i class="far fa-star-half fa-stack-1x fa-flip-horizontal"></i>
                        </div>
                    <?php }
                } ?>
            </div>
            <?php } ?>
            <p class="wsb-testimonial-text"><?= esc_html( $testimonial->content ); ?></p>
        </div>
    <?php } ?>
</div>
