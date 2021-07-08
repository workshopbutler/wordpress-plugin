<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $trainer->url ) { ?>
    <a href="<?= esc_attr($trainer->url) ?>"><?= esc_html($trainer->get_full_name()) ?></a>
<?php } else { ?>
    <div><?= esc_html($trainer->get_full_name()) ?></div>
<?php
}
