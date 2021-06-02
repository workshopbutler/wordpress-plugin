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

if ( $trainer->url ) { ?>
    <a href="<?= esc_attr($trainer->url) ?>">
        <div class="wsb-trainer-tile_img" style="background-image: url(<?= esc_attr($trainer->photo) ?>);"></div>
    </a>
<?php } else { ?>
    <div class="wsb-trainer-tile_img" style="background-image: url(<?= esc_attr($trainer->photo) ?>);"></div>
<?php
}
