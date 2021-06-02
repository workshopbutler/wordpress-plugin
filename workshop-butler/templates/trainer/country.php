<?php
/**
 * Trainer country
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();
?>

<div class="wsb-trainer-title">
    <div class="wsb-trainer-country">
    <?php echo esc_html__('country.'.$trainer->country_code, 'wsbintegration'); ?></div>
</div>
