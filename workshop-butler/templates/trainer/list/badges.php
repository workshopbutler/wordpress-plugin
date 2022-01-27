<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="wsb-trainer-tile__badges">
    <?php foreach ( $trainer->badges as $badge ) { ?>
        <img src="<?php echo esc_attr($badge->url) ?>" width="30px"/>
    <?php } ?>
</div>
