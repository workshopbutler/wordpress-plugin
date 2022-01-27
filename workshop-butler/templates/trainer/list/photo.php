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
    <a href="<?php echo esc_attr($trainer->url) ?>">
        <div class="wsb-trainer-tile__img" style="background-image: url(<?php echo esc_attr($trainer->get_photo_or_default()) ?>);"></div>
    </a>
<?php } else { ?>
    <div class="wsb-trainer-tile__img" style="background-image: url(<?php echo esc_attr($trainer->get_photo_or_default()) ?>);"></div>
<?php
}
