<?php
/**
 * Trainer testimonials
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$testimonials = $trainer->testimonials;

if ( !$testimonials ) {
    return;
}

?>

<div class="owl-carousel owl-carousel-testimonial">
    <?php foreach ( $testimonials as $testimonial ) { ?>
    <div class="wsb-testimonials-slide">
        <?php  if ( $testimonial->rating ) {  ?>
            <div class="wsb-testimonials-stars">
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

        <div class="wsb-testimonials-desc">
            <?php echo esc_html( wp_trim_words($testimonial->content, 35, '...') ); ?>
        </div>

        <div class="wsb-cite">
            <div class="wsb-cite__inner">
                <?php  if ( $testimonial->avatar ) { ?>
                    <img class="userpic" src="<?php echo esc_attr( $testimonial->avatar ); ?>"/>
                <?php } ?>
                <div>
                <div class="who">
                    <strong><?php echo esc_html( $testimonial->attendee ); ?></strong>
                    <?php  if ( $testimonial->is_verified ) { ?><span class="wsb-verified"></span><?php } ?>
                    <?php  if ( $testimonial->company ) { echo esc_html( $testimonial->company ); } ?>
                </div>

                <div class="to-what">
                    <?php  if ( $testimonial->reason ) { echo esc_html( $testimonial->reason ); } ?>
                </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<div class="wsb-testimonials-counter" style="display:none;">
    <span class="current">1</span> / <span class="total">0</span>
</div>
