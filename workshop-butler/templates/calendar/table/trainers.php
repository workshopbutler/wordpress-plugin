<?php
/**
 * Event trainers on the event row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Event_Calendar_Config $config
 */

$without_name = $config->is_show_trainer_name() ? '' : 'without-name';
?>
<div class="wsb-table__col wsb-table__col-trainers <?php echo esc_attr( $without_name ); ?>">
	<?php foreach ( $event->trainers as $trainer ) { ?>
		<div class="wsb-trainer">
			<?php if ( $trainer->url ) { ?>
				<a href="<?php echo esc_attr( $trainer->url ); ?>">
					<img class="wsb-photo" src="<?php echo esc_attr( $trainer->get_photo_or_default() ); ?>"
							alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>"/>
				</a>
				<a class="wsb-name" href="<?php echo esc_attr( $trainer->url ); ?>">
					<?php
					if ( $config->is_show_trainer_name() ) {
						echo esc_html( $trainer->get_full_name() );
					}
					?>
				</a>
			<?php } else { ?>
				<img class="wsb-photo" src="<?php echo esc_attr( $trainer->get_photo_or_default() ); ?>"
						alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>"/>
				<?php
				if ( $config->is_show_trainer_name() ) {
					echo esc_html( $trainer->get_full_name() );
				}
				?>
			<?php } ?>
		</div>
	<?php } ?>
</div>
