<?php
/**
 * The file that defines Ticket_price class
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

/**
 * This class represents a price of a ticket in Workshop Butler
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Ticket_Price {
	/**
	 * @since   0.2.0
	 * @var     float
	 */
	public $amount;

	/**
	 * @since   0.2.0
	 * @var     string
	 */
	public $currency;

	/**
	 * @since   0.2.0
	 * @var     string
	 */
	public $sign;

	/**
	 * Creates a new price
	 *
	 * @param $jsonData object
	 */
	public function __construct( $jsonData ) {
		$this->amount   = $jsonData->amount;
		$this->currency = $jsonData->currency;
		$this->sign     = $jsonData->sign;
	}
}
