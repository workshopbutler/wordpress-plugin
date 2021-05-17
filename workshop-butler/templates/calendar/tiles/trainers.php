<?php
/**
 * Event trainers on the event tile
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

$event  = WSB()->dict->get_event();
$config = WSB()->dict->get_schedule_config();

is_a( $event, 'WorkshopButler\Event' ) || exit();
?>
<div class="wsb-tile-content">
	<?php foreach ( $event->trainers as $trainer ) { ?>
		<div class="wsb-trainer">
			<?php if ( $trainer->get_url() ) { ?>
				<a href="<?php echo esc_attr( $trainer->get_url() ); ?>">
					<img class="wsb-photo" src="<?php echo esc_attr( $trainer->get_photo() ); ?>"
							alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>"/>
				</a>
				<a class="wsb-name" href=" <?php echo esc_attr( $trainer->get_url() ); ?>">
					<?php
					if ( $config->is_show_trainer_name() ) {
						echo esc_html( $trainer->get_full_name() );
					}
					?>
				</a>
			<?php } else { ?>
				<img class="wsb-photo" src="<?php echo esc_attr( $trainer->get_photo() ); ?>"
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

