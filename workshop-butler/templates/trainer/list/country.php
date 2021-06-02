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

if( !$trainer->country_code ) {
    return;
}

?>
<div class="wsb-trainer-tile__country">
    <?=  esc_html__("country.".$trainer->country_code, 'wsbintegration') ?>
</div>
