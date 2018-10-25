<?php
/**
 * The file that defines Tickets class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * This class represents all available event tickets
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Tickets {

	/**
	 * Paid tickets if the event is paid
	 *
	 * @since 2.0.0
	 * @var   Paid_Ticket_Type[] $paid
	 */
	public $paid;

	/**
	 * Free tickets if the event is free
	 *
	 * @since 2.0.0
	 * @var   Free_Ticket_Type $free
	 */
	public $free;

	/**
	 * Initialises new tickets
	 *
	 * @param Paid_Ticket_Type[] $paid_tickets Paid tickets if the event is paid.
	 * @param Free_Ticket_Type   $free_ticket  Free ticket if the event is free.
	 */
	public function __construct( $paid_tickets, $free_ticket ) {
		$this->free = $free_ticket;
		$this->paid = $paid_tickets;
	}

	/**
	 * Returns true if the event either has no paid tickets or has an unlimited number of free tickets
	 *
	 * @return boolean
	 */
	public function is_empty() {
		if ( $this->free ) {
			return $this->free->without_limit();
		} else {
			return count( $this->paid ) === 0;
		}
	}

	/**
	 * Returns number of all tickets left, for all valid and future types, or null if there is no limitation
	 *
	 * @return int | null
	 */
	public function get_number_of_seats_left() {
		/**
		 * Helper function to calculate a total number of seats
		 *
		 * @param int $total  Total number of seats.
		 * @param int $number Additional number of seats.
		 * @return int
		 */
		function sum( $total, $number ) {
			return $total + $number;
		}

		if ( $this->free ) {
			if ( $this->free->number_of_tickets_left >= 0 ) {
				return $this->free->number_of_tickets_left;
			} else {
				return null;
			}
		} else {
			$active = array_map(
				function ( $event ) {
					return $event->number_of_tickets_left;
				},
				$this->get_active()
			);

			$future = array_map(
				function ( $event ) {
					return $event->number_of_tickets_left;
				},
				$this->get_future()
			);

			return array_reduce( $active, 'sum', 0 ) + array_reduce( $future, 'sum', 0 );
		}
	}

	/**
	 * Returns active paid types, which a user can register to
	 *
	 * @return Paid_Ticket_Type[]
	 */
	protected function get_active() {
		return array_filter(
			$this->paid,
			function ( $ticket ) {
				return $ticket->is_active();
			}
		);
	}

	/**
	 * Returns future paid types, which a user can register to
	 *
	 * @return Paid_Ticket_Type[]
	 */
	protected function get_future() {
		return array_filter(
			$this->paid,
			function ( $ticket ) {
				return $ticket->is_in_future();
			}
		);
	}

	/**
	 * Returns true if the event has tickets to buy/acquire
	 */
	public function non_empty() {
		return ! $this->is_empty();
	}

}
