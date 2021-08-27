<?php
/**
 * Trainer email
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<a class="btn btn-primary wsb-form__btn wsb-contact-button" href="mailto:<?= esc_attr( $trainer->email ); ?>"
   title="<?= esc_attr__('trainer.email', 'wsbintegration'); ?>"><?= esc_html__('trainer.email', 'wsbintegration'); ?></a>
