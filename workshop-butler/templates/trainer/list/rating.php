<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $trainer->stats->total->public_stats->rating > 0 ) { ?>
    <div class="wsb-trainer-tile__rating">
        <?= $trainer->stats->total->public_stats->get_rounded_rating() ?>
        <i class="fas fa-star"></i>
    </div>
<?php }
