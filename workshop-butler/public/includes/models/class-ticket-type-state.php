<?php
/**
 * The file that defines Ticket_Type_State class
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

/**
 * This class represents a state of the ticket (sold out, ended, etc) in Workshop Butler
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Ticket_Type_State {

	/**
	 * @since   0.2.0
	 * @var     boolean $sold_out
	 */
	public $sold_out;

	/**
	 * @since   0.2.0
	 * @var     boolean $ended
	 */
	public $ended;

	/**
	 * @since   0.2.0
	 * @var     boolean $started
	 */
	public $started;

	/**
	 * @since   0.2.0
	 * @var     boolean $in_future
	 */
	public $in_future;

	/**
	 * @since   0.2.0
	 * @var     boolean $valid
	 */
	public $valid;

	/**
	 * Initialises a new ticket state
	 *
	 * @param $jsonData object JSON for a ticket state
	 */
	public function __construct( $jsonData ) {
		$this->sold_out  = $jsonData->sold_out;
		$this->ended     = $jsonData->ended;
		$this->started   = $jsonData->started;
		$this->in_future = $jsonData->in_future;
		$this->valid     = $jsonData->valid;
	}
}
