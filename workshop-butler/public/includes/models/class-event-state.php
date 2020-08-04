<?php
/**
 * The file that defines the Event_State class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-free-ticket-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-paid-tickets.php';

/**
 * Represents an event's state
 *
 * @since 2.0.0
 */
class Event_State {
	/**
	 * Related event
	 *
	 * @var Event $event
	 * @since 2.0.0
	 */
	protected $event;

	/**
	* True if the state is canceled
	*
	* @since   2.13.3
	* @var     boolean $canceled
	*/
	protected $canceled;


	/**
	 * Initialises a new state
	 *
	 * @param Event $event Related event.
	 */
	public function __construct( $event, $canceled = False) {
		$this->event = $event;
		$this->canceled = $canceled;
	}

	/**
	 * Returns true if the registrations for this event are open
	 *
	 * @return boolean
	 * @since 2.0.0
	 */
	public function open() {
		return ! $this->closed();
	}

	/**
	 * Returns true if the registrations for this event are closed
	 *
	 * @return boolean
	 * @since 2.0.0
	 */
	public function closed() {
		if ( $this->event->schedule->ended() ) {
			return true;
		} elseif ( $this->event->private ) {
			return true;
		} elseif ( $this->canceled ) {
			return true;
		} elseif ( ! $this->event->tickets ) {
			return false;
		} elseif ( $this->event->tickets instanceof Free_Ticket_Type && $this->event->tickets->sold_out() ) {
			return true;
		} elseif ( $this->event->tickets instanceof Paid_Tickets && count( $this->event->tickets->types ) > 0 ) {
			$sold_out = true;
			foreach ( $this->event->tickets->types as $ticket_type ) {
				if ( $ticket_type->active() ) {
					$sold_out = false;
				}
			}

			return $sold_out;
		} else {
			return false;
		}
	}

	/**
	 * Returns the reason why the registrations are closed or 'null' if they are open
	 *
	 * @return string | null
	 * @since 2.0.0
	 */
	public function reason() {
		if ( $this->event->schedule->ended() ) {
			return 'event.state.ended';
		} elseif ( $this->event->private ) {
			return 'event.state.private';
		} elseif ( $this->canceled ) {
			return 'event.state.canceled';
		} elseif ( ! $this->event->tickets ) {
			return null;
		} elseif ( $this->event->tickets instanceof Free_Ticket_Type && $this->event->tickets->sold_out() ) {
			return 'event.state.soldOut';
		} elseif ( $this->event->tickets instanceof Paid_Tickets && count( $this->event->tickets->types ) > 0 ) {
			$sold_out = true;
			foreach ( $this->event->tickets->types as $ticket_type ) {
				if ( $ticket_type->active() ) {
					$sold_out = false;
				}
			}
			return $sold_out ? 'event.state.soldOut' : null;
		} else {
			return null;
		}
	}

}
