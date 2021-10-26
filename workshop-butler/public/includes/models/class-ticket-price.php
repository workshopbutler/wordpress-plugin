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

		return new Ticket_Price(
			$json->amount / 100,
			$json->tax / 100,
			$json->currency,
			isset( $json->sign ) ? $json->sign : null
		);
	}

	/**
	 * Price amount
	 *
	 * @since   2.0.0
	 * @var     float
	 */
	public $amount;

	/**
	 * Tax amount
	 *
	 * @since 3.1.0
	 * @var number|null $tax
	 */
	public $tax;

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
	public function __construct( $amount, $tax, $currency, $sign ) {
		$this->amount   = $amount;
		$this->tax      = $tax;
		$this->currency = $currency;
		$this->sign     = $sign;
	}

	/**
	 * Returns the price's amount with tax
	 *
	 * @since 3.1.0
	 * @return float
	 */
	public function get_amount_with_tax() {
		return $this->amount + $this->tax;
	}
}
