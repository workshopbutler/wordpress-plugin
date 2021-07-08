<?php
/**
 * Trainer photo
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$url = $trainer->photo ?  $trainer->photo : 'default-trainer.svg';

?>

<img src="<?= esc_attr( $url  ); ?>" alt="<?= esc_attr( $trainer->get_full_name() ); ?>"width="100%"/>
