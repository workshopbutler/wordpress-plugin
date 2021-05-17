<?php
/**
 * Event trainers on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$event = WSB()->dict->get_event();

is_a( $event, 'WorkshopButler\Event' ) || exit();
?>
<div class="wsb-trainers">
	<?php foreach ( $event->get_trainers() as $trainer ) { ?>
		<div class="wsb-trainer">
			<div class="wsb-profile">
				<?php if ( $trainer->get_url() ) { ?>
					<a href="<?php echo esc_attr( $trainer->get_url() ); ?>"
							class="wsb-profile_img"
							style="background-image: url(<?php echo esc_attr( $trainer->get_photo() ); ?>);"></a>
				<?php } else { ?>
					<div class="wsb-profile-img">
						<img src="<?php echo esc_attr( $trainer->get_photo() ); ?>"
								alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>"/>
					</div>
				<?php } ?>
				<div class="wsb-profile__name">
					<?php if ( $trainer->get_url() ) { ?>
						<a href="<?php echo esc_attr( $trainer->get_url() ); ?>"><?php echo esc_html( $trainer->get_full_name() ); ?></a>
						<?php
					} else {
						echo esc_html( $trainer->get_full_name() );
					}
					?>
					<div class="wsb-profile__rating">
						<?php
						if ( $trainer->stats->total->public_stats->rating > 0 ) {
							$rating = round( $trainer->stats->total->public_stats->rating, PHP_ROUND_HALF_DOWN );
							$stars  = array( 0, 1, 2, 3, 4 );
							foreach ( $stars as $position ) {
								$index = $position + 1;
								if ( $position * 2 <= $rating ) {
									?>
									<i class="fas fa-star"></i>
									<?php
								} else {
									if ( $position * 2 <= $rating + 1 ) {
										?>
										<i class="fas fa-star-half fa-stack-1x"></i>
										<i class="far fa-star-half fa-stack-1x fa-flip-horizontal"></i>
										<?php
									}
								}
							}
						}
						echo esc_html( Formatter::format( $trainer->stats->total->public_stats->rating ) );
						?>
						<span>
							<?php
							$token = _n( 'trainer.experience.rating.review', 'trainer.experience.rating.review', $trainer->stats->total->public_stats->evaluations, 'wsbintegration' );
							echo esc_html( sprintf( $token, $trainer->stats->total->public_stats->evaluations ) );
							?>
						</span>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

