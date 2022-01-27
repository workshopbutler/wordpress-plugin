<?php
/**
 * Trainer badges
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="wsb-trainer-badges">
    <?php foreach ( $trainer->badges as $badge ) { ?>
        <img alt="<?php echo esc_attr( $badge->name ); ?>" class="wsb-trainer__badge" src="<?php echo esc_attr( $badge->url ); ?>">
    <?php } ?>
</div>
