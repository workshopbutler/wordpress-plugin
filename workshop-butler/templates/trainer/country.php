<?php
/**
 * Trainer country
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( !$trainer->country_code ) {
    return;
}

?>

<div class="wsb-trainer-country"><?php echo esc_html__('country.'.$trainer->country_code, 'wsbintegration'); ?></div>
