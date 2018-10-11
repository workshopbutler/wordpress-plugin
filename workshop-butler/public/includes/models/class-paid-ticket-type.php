<?php
/**
 * The file that defines Paid_Ticket_type class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-price.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-type-state.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-type.php';

/**
 * This class represents a paid ticket type in a Workshop Butler event
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Paid_Ticket_Type extends Ticket_Type {
	/**
	 * ID of the type
	 *
	 * @since  2.0.0
	 * @var    int $id ID of the type
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
	 * @var    DateTime $start Date when the tickets of this type go on sale
	 */
	public $start;

	/**
	 * Date when sales of the tickets of this type end
	 *
	 * @since  2.0.0
	 * @var    DateTime $end Date when sales of the tickets of this type end
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
	 * @since   2.0.0
	 * @var     Ticket_Price $price
	 */
	public $price;

	/**
	 * @since   2.0.0
	 * @var     Ticket_Type_State $state
	 */
	private $state;

	/**
	 * Creates a new paid ticket type from JSON
	 *
	 * @param $jsonData object JSON for a ticket type
	 */
	public function __construct( $jsonData ) {
		$this->id                     = $jsonData->id;
		$this->name                   = $jsonData->name;
		$this->number_of_tickets      = $jsonData->amount;
		$this->number_of_tickets_left = $jsonData->left;
		$this->start                  = new DateTime( $jsonData->start );
		$this->end                    = new DateTime( $jsonData->end );
		$this->excluded_tax           = ! $jsonData->with_vat;
		$this->price                  = new Ticket_Price( $jsonData->price );
		$this->state                  = new Ticket_Type_State( $jsonData->state );
	}

	/**
	 * Returns true if the tickets of this type can be bought
	 *
	 * @since  2.0.0
	 * @return boolean
	 */
	public function active() {
		return $this->state->valid;
	}

	/**
	 * Returns true if the tickets of this type can be bought later, in future
	 *
	 * @since  2.0.0
	 * @return boolean
	 */
	public function in_future() {
		return $this->state->in_future;
	}

	/**
	 * Returns true if no more seats left
	 *
	 * @since  2.0.0
	 * @return boolean
	 */
	public function sold_out() {
		return $this->state->sold_out;
	}

	/**
	 * Returns true if the sales of tickets of this type have ended
	 *
	 * @since  2.0.0
	 * @return boolean
	 */
	public function ended() {
		return $this->state->ended;
	}

	/**
	 * Returns true if there is no limitation for a number of tickets
	 */
	public function without_limit() {
		return false;
	}

}
