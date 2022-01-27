<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $trainer->get_displayed_public_rating() ) { ?>
    <div class="wsb-trainer-tile__rating">
        <?php echo esc_html( $trainer->get_displayed_public_rating() ); ?>
        <i class="fas fa-star"></i>
    </div>
<?php }
