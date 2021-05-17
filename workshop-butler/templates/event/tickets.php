<?php
/**
 * Tickets of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();
$config = WSB()->dict->get_single_event_config();

if ( $event->with_tickets() ) {
	?>
	<div class="wsb-tickets wsb-info">
		<?php if ( $event->is_free() ) { ?>
			<div class="wsb-ticket">
				<div class="wsb-ticket__val">
					<?php echo esc_html__( 'event.ticket.free', 'wsbintegration' ); ?>
				</div>
				<div class="wsb-ticket__name">
					<?php echo esc_html__( 'event.ticket.freeDescr', 'wsbintegration' ); ?>
				</div>
				<div class="wsb-ticket__footer">
					<?php echo esc_html( Formatter::format( $event->get_tickets(), 'state' ) ); ?>
				</div>
			</div>
			<?php
		} else {
			foreach ( $event->get_tickets()->get_types() as $ticket ) {
				$closed  = $ticket->active() ? '' : 'state-closed';
				$visible = $config->is_show_expired_tickets() || $ticket->active() || $ticket->in_future();
				if ( $visible ) {
					?>
					<div class="wsb-ticket <?php echo esc_attr( $closed ); ?>">
						<div class="wsb-ticket__val">
							<?php echo esc_html( Formatter::format( $ticket, 'price' ) ); ?>
						</div>
						<div class="wsb-ticket__name">
							<?php echo esc_html( $ticket->get_name() ); ?>
						</div>
						<?php
						$description = Formatter::format( $ticket, 'desc' );
						if ( $description ) {
							?>
							<div class="wsb-ticket__desc">
								<?php echo esc_html( $description ); ?>
							</div>
							<?php
						}
						if ( ! $ticket->ended() && $config->is_show_number_of_tickets() ) {
							?>
							<div class="wsb-ticket__footer">
								<?php echo esc_html( Formatter::format( $ticket, 'state' ) ); ?>
							</div>
						<?php } ?>
					</div>
					<?php
				}
			}
		}
		?>
	</div>
	<?php
}
