<?php
/**
 * Tickets of the event
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Single_Event_Config $config
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $event->with_tickets() ) {
	?>
	<div class="wsb-tickets">
		<?php if ( $event->is_free ) { ?>
			<div class="wsb-ticket">
				<div class="wsb-ticket__val">
					<?php echo esc_html__( 'event.ticket.free', 'wsbintegration' ); ?>
				</div>
				<div class="wsb-ticket__name">
					<?php echo esc_html__( 'event.ticket.freeDescr', 'wsbintegration' ); ?>
				</div>
				<div class="wsb-ticket__footer">
					<?php echo esc_html( Formatter::format( $event->tickets, 'state' ) ); ?>
				</div>
			</div>
			<?php
		} else {
			foreach ( $event->tickets->types as $ticket ) {
				$closed  = $ticket->active() ? '' : 'state-closed';
				$visible = $config->is_show_expired_tickets() || $ticket->active() || $ticket->in_future();
				if ( $visible ) {
					?>
					<div class="wsb-ticket <?php echo esc_attr( $closed ); ?>">
						<div class="wsb-ticket__val">
							<?php echo esc_html( Formatter::format( $ticket, 'price' ) ); ?>
						</div>
						<div class="wsb-ticket__name">
							<?php echo esc_html( $ticket->name ); ?>
						</div>
						<div class="wsb-ticket__footer">
						<?php
							$description = Formatter::format( $ticket, 'desc' );
							if ( $description ) {
								echo esc_html( $description );
							}

							if ( ! $ticket->ended() && $config->is_show_number_of_tickets() ) {
								if ( $description ) {
									echo ' • ';
								}
								echo esc_html( Formatter::format( $ticket, 'state' ) );

							} ?>
						</div>
					</div>
					<?php
				}
			}
		}
		?>
	</div>
	<?php
}
