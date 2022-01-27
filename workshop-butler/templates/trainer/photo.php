<?php
/**
 * Trainer photo
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<img src="<?php echo esc_attr(  $trainer->get_photo_or_default()  ); ?>" alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>"width="100%"/>
