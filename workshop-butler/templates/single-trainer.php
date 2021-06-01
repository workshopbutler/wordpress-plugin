<?php
/**
 * Template for the single trainer.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();
$settings = WSB()->settings;
$theme    = $settings->get_theme();
?>
<div class="<?php echo esc_attr( $theme ); ?>">
    <div class="wsb-content">
        <div class="wsb-page">
            <div class="wsb-body wsb-trainer-profile">
                <div class="wsb-trainer__header">
                    <div class="wsb-trainer__header-row">
						<?php do_action( 'wsb_trainer_photo' ); ?>
                        <div class="wsb-trainer__header-col">
                            <div class="wsb-trainer-buttons">
                                <div class="wsb-trainer__social-networks">
									<?php do_action( 'wsb_trainer_social_links' ); ?>
                                </div>
								<?php do_action( 'wsb_trainer_email' ); ?>
                            </div>
							<?php do_action( 'wsb_trainer_country' ); ?>
                        </div>
                    </div>
                    <div class="wsb-trainer__header-row">
                        <div class="wsb-trainer-details-facts">
							<?php do_action( 'wsb_trainer_stats' ); ?>
                        </div>
                    </div>
                </div>
                <div class="wsb-toolbar wsb-first">
					<?php do_action( 'wsb_trainer_badges' ); ?>
                </div>
                <div class="wsb-description">
					<?php do_action( 'wsb_trainer_bio' ); ?>
					<?php do_action( 'wsb_trainer_testimonials' ); ?>

                </div>
                <div class="wsb-toolbar wsb-second">
                    <div class="wsb-trainer-events">
						<?php do_action( 'wsb_trainer_future_events' ); ?>
                        <?php do_action( 'wsb_trainer_past_events' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
