<?php
/**
 * Trainer photo
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$url = $trainer->photo;

if ( $url ) {
?>
<img src="<?= esc_attr( $url ); ?>"
	alt="<?= esc_attr( $trainer->get_full_name() ); ?>" width="100%"/>
<?php } ?>
