<?php
/**
 * Ticket field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$field = WSB()->dict->get_form_field();
is_a( $field, 'WorkshopButler\Ticket' ) || exit();
$event = WSB()->dict->get_event();
is_a( $event, 'WorkshopButler\Event' ) || exit();

$next_loop = false;
?>
<div class="wsb-form__tickets" id="wsb-tickets">
	<?php
	foreach ( $field->get_tickets()->get_types() as $ticket ) {
		if ( $ticket->active() ) {
			?>
			<div class="wsb-form__ticket">
				<input id="<?php echo esc_attr( $ticket->get_id() ); ?>"
						name="<?php echo esc_attr( $field->get_name() ); ?>"
						title="<?php echo esc_attr( $field->get_label() ); ?>"
						type="radio"
						data-control required value="<?php echo esc_attr( $ticket->get_id() ); ?>"
						data-amount="<?php echo esc_attr( $ticket->get_price()->get_amount() ); ?>"
						data-currency="<?php echo esc_attr( $ticket->get_price()->get_currency() ); ?>"
					<?php
					if ( ! $next_loop ) {
						echo 'checked';
					}
					?>/>

				<label for="<?php echo esc_attr( $ticket->get_id() ); ?>" class="wsb-label">
					<strong><?php echo esc_html( Formatter::format( $ticket, 'price' ) ); ?></strong> <?php echo esc_html( $ticket->get_name() ); ?>
				</label>
			</div>
			<?php
			$next_loop = true;
		}
	}
	?>
	<div class="wsb-form__tax">
		<?php
		if ( $field->get_tickets()->is_tax_excluded() ) {
			echo esc_html__( 'tax.excluded_all', 'wsbintegration' );
			if ( $field->get_tickets()->get_tax() ) {
				echo esc_html( $field->get_tickets()->get_tax() );
			}
		} else {
			echo esc_html__( 'tax.included_all', 'wsbintegration' );
		}
		?>
	</div>
</div>
