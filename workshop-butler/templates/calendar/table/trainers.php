<?php
/**
 * Event trainers on the event row
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

$event  = WSB()->dict->get_event();
$config = WSB()->dict->get_schedule_config();

is_a( $event, 'WorkshopButler\Event' ) || exit();
$without_name = $config->is_show_trainer_name() ? '' : 'without-name';
?>
<div class="wsb-table__col wsb-table__col-trainers <?php echo esc_attr( $without_name ); ?>">
	<?php foreach ( $event->trainers as $trainer ) { ?>
		<div class="wsb-trainer">
			<?php if ( $trainer->get_url() ) { ?>
				<a href="<?php echo esc_attr( $trainer->get_url() ); ?>">
					<img class="wsb-photo" src="<?php echo esc_attr( $trainer->get_photo() ); ?>"
							alt="<?php echo esc_attr( $trainer->get_full_name() ); ?>"/>
				</a>
				<a class="wsb-name" href="<?php echo esc_attr( $trainer->get_url() ); ?>">
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

