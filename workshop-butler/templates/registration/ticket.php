<?php
/**
 * Ticket field of the registration form
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Field $field
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$next_loop = false;
?>
<div class="wsb-form__tickets" id="wsb-tickets">
	<?php
	foreach ( $field->tickets->get_types() as $ticket ) {
		if ( $ticket->active() ) {
			?>
			<div class="wsb-form__radio">
				<input id="<?= esc_attr( $ticket->get_id() ); ?>"
						name="<?= esc_attr( $field->get_name() ); ?>"
						title="<?= esc_attr( $field->get_label() ); ?>"
						type="radio"
						data-control required value="<?= esc_attr( $ticket->get_id() ); ?>"
						data-amount="<?= esc_attr( $ticket->price->amount ); ?>"
						data-tax="<?= esc_attr( $ticket->price->tax ); ?>"
						data-currency="<?= esc_attr( $ticket->price->currency ); ?>"
					<?php
					if ( ! $next_loop ) {
						echo 'checked';
					}
					?>/>

				<label for="<?= esc_attr( $ticket->get_id() ); ?>" class="wsb-label">
					<strong><?= esc_html( Formatter::format( $ticket, 'price' ) ); ?></strong>
					<?= esc_html( $ticket->name ); ?>
					<?php if ( $field->tickets->excluded_tax && $ticket->price->tax > 0 ) { ?>
						<span class="wsb-ticket__tax">
						+ <? printf( esc_html__( 'tax.amount', 'wsbintegration' ), Formatter::format( $ticket, 'tax' ) ); ?>
						</span>
					<?php } ?>
				</label>
			</div>
			<?php
			$next_loop = true;
		}
	}
	?>
	<div class="wsb-form__tax" data-tax-description>
		<?php
		if ( $field->tickets->excluded_tax ) {
			echo esc_html__( 'tax.excluded_all', 'wsbintegration' );
			if ( $field->tickets->tax_rate ) {
				printf(" %s%%", esc_html( $field->tickets->tax_rate ));
			}
			if ( $field->tickets->validate_tax ) {
				printf(" <a data-vat-apply-link>%s</a>", esc_html__( 'tax.widget.apply-link', 'wsbintegration' ));
			}
		} else {
			echo esc_html__( 'tax.included_all', 'wsbintegration' );
		}
		?>
	</div>
</div>
