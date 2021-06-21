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

?>
<?php foreach ( $trainer->badges as $badge ) { ?>
    <img src="<?= esc_attr($badge->url) ?>" width="30px"/>
<?php } ?>