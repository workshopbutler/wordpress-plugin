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
	 * Creates a new ticket price.
	 *
	 * @param object $json JSON value.
	 *
	 * @return Ticket_Price
	 */
	static function from_json( $json ) {
		$amount = $json->amount / 100;

		return new Ticket_Price( $amount, $json->currency, $json->sign );
	}

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
	 * @var     string|null
	 */
	public $sign;

	/**
	 * Creates a new price
	 *
	 * @param float       $amount Price amount.
	 * @param string      $currency 3-letter currency code.
	 * @param string|null $sign Currency sign.
	 */
	public function __construct( $amount, $currency, $sign ) {
		$this->amount   = $amount;
		$this->currency = $currency;
		$this->sign     = $sign;
	}
}
