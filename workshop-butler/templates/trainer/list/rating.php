<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();

if ( $trainer->stats->total->public_stats->rating > 0 ) { ?>
    <i class="fas fa-star"></i>
    <?= esc_html($trainer->stats->total->public_stats->rating) ?>
<?php }
