<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if( !$trainer->country_code ) {
    return;
}

?>
<div class="wsb-trainer-tile__country">
    <?=  esc_html__("country.".$trainer->country_code, 'wsbintegration') ?>
</div>
