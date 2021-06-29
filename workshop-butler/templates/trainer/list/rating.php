<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $trainer->stats->total->public_stats->rating > 0 ) { ?>
    <i class="fas fa-star"></i>
    <?= esc_html($trainer->stats->total->public_stats->rating) ?>
<?php }
