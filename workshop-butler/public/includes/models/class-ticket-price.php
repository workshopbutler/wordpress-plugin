<?php
/**
 * The file that defines Ticket_price class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * This class represents a price of a ticket in Workshop Butler
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Ticket_Price {
	/**
	 * Price amount
	 *
	 * @since   2.0.0
	 * @var     float
	 */
	public $amount;

	/**
	 * Currency (3-letter code)
	 *
	 * @since   2.0.0
	 * @var     string
	 */
	public $currency;

	/**
	 * Currency sign ($ or €)
	 *
	 * @since   2.0.0
	 * @var     string
	 */
	public $sign;

	/**
	 * Creates a new price
	 *
	 * @param object $json_data JSON representation of ticket price.
	 */
	public function __construct( $json_data ) {
		$this->amount   = $json_data->amount;
		$this->currency = $json_data->currency;
		$this->sign     = $json_data->sign;
	}
}
