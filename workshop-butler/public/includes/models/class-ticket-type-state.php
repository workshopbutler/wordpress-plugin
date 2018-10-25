<?php
/**
 * The file that defines Ticket_Type_State class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * This class represents a state of the ticket (sold out, ended, etc) in Workshop Butler
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Ticket_Type_State {

	/**
	 * True if the ticket type is sold out
	 *
	 * @since   2.0.0
	 * @var     boolean $sold_out
	 */
	public $sold_out;

	/**
	 * True if the sales period has ended
	 *
	 * @since   2.0.0
	 * @var     boolean $ended
	 */
	public $ended;

	/**
	 * True if the sales period has started
	 *
	 * @since   2.0.0
	 * @var     boolean $started
	 */
	public $started;

	/**
	 * True if the sales period is in the future
	 *
	 * @since   2.0.0
	 * @var     boolean $in_future
	 */
	public $in_future;

	/**
	 * True if it's allowed to sell the tickets of this ticket type
	 *
	 * @since   2.0.0
	 * @var     boolean $valid
	 */
	public $valid;

	/**
	 * Initialises a new ticket state
	 *
	 * @param object $json_data JSON for a ticket state.
	 */
	public function __construct( $json_data ) {
		$this->sold_out  = $json_data->sold_out;
		$this->ended     = $json_data->ended;
		$this->started   = $json_data->started;
		$this->in_future = $json_data->in_future;
		$this->valid     = $json_data->valid;
	}
}
