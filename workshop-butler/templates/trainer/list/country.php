<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if( !$trainer->country_code ) {
    return;
}

?>
<div class="wsb-trainer-tile__country">
    <span class="flag-icon flag-icon-<?= strtolower( $trainer->country_code ) ?> wsb-flag"></span>
	<span><?= esc_html__('country.'.$trainer->country_code, 'wsbintegration'); ?></span>
</div>
