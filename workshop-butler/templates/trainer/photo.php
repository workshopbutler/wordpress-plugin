<?php
/**
 * Trainer photo
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();

$url = $trainer->photo;

if ( $url ) {
?>
<img src="<?php echo esc_attr( $url ); ?>"
	alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>" width="100%"/>
<?php } ?>
