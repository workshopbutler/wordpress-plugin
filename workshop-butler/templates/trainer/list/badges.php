<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<?php foreach ( $trainer->badges as $badge ) { ?>
    <img src="<?= esc_attr($badge->url) ?>" width="30px"/>
<?php } ?>
