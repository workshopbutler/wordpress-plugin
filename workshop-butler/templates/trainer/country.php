<?php
/**
 * Trainer country
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="wsb-trainer-title">
    <div class="wsb-trainer-country">
    <?= esc_html__('country.'.$trainer->country_code, 'wsbintegration'); ?></div>
</div>
