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
    <a href="<?php echo esc_attr($trainer->url) ?>"><?php echo esc_html($trainer->get_full_name()) ?></a>
<?php } else { ?>
    <div><?php echo esc_html($trainer->get_full_name()) ?></div>
<?php
}
