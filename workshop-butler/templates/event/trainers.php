<?php
/**
 * Event trainers on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Single_Event_Config $config
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="wsb-trainers">
	<?php foreach ( $event->trainers as $trainer ) { ?>
		<div class="wsb-trainer wsb-profile">
			<?php if ( $trainer->url ) { ?>
				<a href="<?= esc_attr( $trainer->url ); ?>"
						class="wsb-profile__img"
						style="background-image: url(<?= esc_attr( $trainer->photo ); ?>);"></a>
			<?php } else { ?>
				<div class="wsb-profile__img"
				style="background-image: url(<?= esc_attr( $trainer->photo ); ?>);"></div>
			<?php } ?>


			<?php if ( $trainer->stats->total->public_stats->rating > 0 ) { ?>
				<div class="wsb-profile__rating">
					<?= $trainer->stats->total->public_stats->get_rounded_rating() ?>
					<i class="fas fa-star"></i>
				</div>
			<?php } ?>


			<div class="wsb-profile__name">
				<?php if ( $trainer->url ) { ?>
					<a href="<?= esc_attr( $trainer->url ); ?>"
						class="wsb-profile__name-link"><?= esc_html( $trainer->get_full_name() ); ?></a>
					<?php
				} else {
					echo esc_html( $trainer->get_full_name() );
				}
				?>
				<?php if( $trainer->country_code ) { ?>
					<div class="wsb-profile__country">
						<span class="flag-icon flag-icon-<?= strtolower( $trainer->country_code ) ?> wsb-flag"></span>
						<span><?= esc_html__('country.'.$trainer->country_code, 'wsbintegration'); ?></span>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
