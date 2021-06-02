<?php
/**
 * Trainer bio
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();
?>
<div class="wsb-desc">
    <?php echo $trainer->bio ?>
</div>
