<?php
/**
 * Trainer email
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();
?>

<a class="btn btn-primary wsb-form__btn wsb-contact-button" href="mailto:<?php echo esc_attr( $trainer->email ); ?>"
   title="<?php echo esc_attr__('trainer.email', 'wsbintegration'); ?>"><?php echo esc_html__('trainer.email', 'wsbintegration'); ?></a>
