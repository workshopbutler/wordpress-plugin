<?php
/**
 * The Template for displaying registration page
 *
 * This template can be overridden by copying it to yourtheme/workshop-butler/registration-page.php.
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global string $theme
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="<?php echo esc_attr( $theme ); ?>">
	<div class="wsb-content">
		<div class="wsb-page wsb-registration-page">
			<div class="wsb-header">
				<h1><?php echo esc_html( $event->title ); ?></h1>
			</div>
			<div class="wsb-body">
				<section class="wsb-info-section">
					<div class="wsb-registration-details">
						<div class="wsb-label">Details</div>
						<?php do_action( 'wsb_event_info' ); ?>
					</div>
					<div class="wsb-registration-details">
						<div class="wsb-label">Trainers</div>
						<?php do_action( 'wsb_event_trainers' ); ?>
					</div>
				</section>
				<?php do_action( 'wsb_registration_form' ); ?>
			</div>
			<div class="wsb-copyright"><a href="https://workshopbutler.com/" target="_blank">Powered by Workshop Butler</a></div>
		</div>
	</div>
</div>
