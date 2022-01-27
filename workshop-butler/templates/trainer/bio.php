<?php
/**
 * Trainer bio
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="wsb-description-text">
    <?php echo wp_kses_post( $trainer->bio ) ?>
</div>
