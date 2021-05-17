<?php
/**
 * The file that defines Paid_Ticket_type class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

use DateTime;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-price.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-type-state.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-type.php';

/**
 * This class represents a paid ticket type in a Workshop Butler event
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Paid_Ticket_Type extends Ticket_Type {

	/**
	 * Creates Paid_Ticket_Type object from JSON
	 *
	 * @param object $json JSON to convert.
	 *
	 * @return Paid_Ticket_Type
	 * @since 2.7.0
	 */
	static function from_json( $json ) {
		$start = $json->start ? new DateTime( $json->start ) : null;
		$end   = $json->end ? new DateTime( $json->end ) : null;
		$price = Ticket_Price::from_json( $json->price );

		return new Paid_Ticket_Type( $json->id, $json->name, $json->total, $json->left, $start, $end, $json->vat_excluded, $price );
	}

	/**
	 * ID of the type
	 *
	 * @since  2.0.0
	 * @var    string $id ID of the type
	 */
	public $id;

	/**
	 * Name of the type
	 *
	 * @since  2.0.0
	 * @var    string $name Name of the type
	 */
	public $name;

	/**
	 * Number of tickets
	 *
	 * @since  2.0.0
	 * @var    int $number_of_tickets Number of tickets
	 */
	public $number_of_tickets;

	/**
	 * Date when the tickets of this type go on sale
	 *
	 * @since  2.0.0
	 * @var    DateTime|null $start Date when the tickets of this type go on sale
	 */
	public $start;

	/**
	 * Date when sales of the tickets of this type end
	 *
	 * @since  2.0.0
	 * @var    DateTime|null $end Date when sales of the tickets of this type end
	 */
	public $end;

	/**
	 * True when a sales tax is NOT included in the price
	 *
	 * @since  2.0.0
	 * @var    boolean $excluded_tax If true, the price of the ticket includes tax
	 */
	public $excluded_tax;

	/**
	 * Price of the ticket
	 *
	 * @since   2.0.0
	 * @var     Ticket_Price $price
	 */
	public $price;

	/**
	 * Creates a new paid ticket type from JSON
	 *
	 * @param string        $id Unique ID.
	 * @param string        $name Name.
	 * @param int           $total Total number of tickets.
	 * @param int           $left Number of tickets left.
	 * @param DateTime|null $start Start datetime of ticket sales.
	 * @param DateTime|null $end End datetime of ticket sales.
	 * @param boolean       $excluded_tax True if the tax is excluded.
	 * @param Ticket_Price  $price Price of the ticket.
	 */
	public function __construct( $id, $name, $total, $left, $start, $end, $excluded_tax, $price ) {
		$this->id                     = $id;
		$this->name                   = $name;
		$this->number_of_tickets      = $total;
		$this->number_of_tickets_left = $left;
		$this->start                  = $start;
		$this->end                    = $end;
		$this->excluded_tax           = $excluded_tax;
		$this->price                  = $price;
	}

	/**
	 * Returns the name of the ticket type
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Returns true if the tickets of this type can be bought
	 *
	 * @return boolean
	 * @since  2.0.0
	 */
	public function active() {
		return ! $this->sold_out() && ! $this->ended() && ! $this->in_future();
	}

	/**
	 * Returns true if the tickets of this type can be bought later, in future
	 *
	 * @return boolean
	 * @since  2.0.0
	 */
	public function in_future() {
		return $this->start && $this->start > new DateTime( 'now' );
	}

	/**
	 * Returns true if no more seats left
	 *
	 * @return boolean
	 * @since  2.0.0
	 */
	public function sold_out() {
		return $this->number_of_tickets_left < 1;
	}

	/**
	 * Returns true if the sales of tickets of this type have ended
	 *
	 * @return boolean
	 * @since  2.0.0
	 */
	public function ended() {
		return $this->end && $this->end < new DateTime( 'now' );
	}

	/**
	 * Returns true if there is no limitation for a number of tickets
	 */
	public function without_limit() {
		return false;
	}

}
