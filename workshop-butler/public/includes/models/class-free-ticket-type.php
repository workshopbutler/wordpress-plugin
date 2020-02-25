<?php
/**
 * The file that defines Free_Ticket_type class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-type.php';

/**
 * This class represents a free ticket type in a Workshop Butler event
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Free_Ticket_Type extends Ticket_Type {

	/**
	 * Creates Free_Ticket_Type object from JSON
	 *
	 * @param object $json JSON to convert.
	 *
	 * @return Free_Ticket_Type
	 * @since 2.7.0
	 */
	static function from_json( $json ) {
		return new Free_Ticket_Type( $json->total, $json->left, $json->unlimited );
	}

	/**
	 * Number of tickets
	 *
	 * @since  2.0.0
	 * @var    int $number_of_tickets Number of tickets
	 */
	public $number_of_tickets;

	/**
	 * If true, there is unlimited amount of free tickets for an event
	 *
	 * @since  2.0.0
	 * @var    boolean $unlimited If true, there is unlimited amount of free tickets for an event
	 */
	private $unlimited;

	/**
	 * Creates a new paid ticket type from JSON
	 *
	 * @param number  $total Total number of tickets.
	 * @param number  $left Number of tickets left.
	 * @param boolean $unlimited True if the number of tickets is unlimited.
	 */
	public function __construct( $total, $left, $unlimited ) {
		$this->number_of_tickets      = $total;
		$this->number_of_tickets_left = $left;
		$this->unlimited              = $unlimited;
	}

	/**
	 * Returns true if no more seats left
	 *
	 * @return boolean
	 * @since  2.0.0
	 */
	public function sold_out() {
		return $this->unlimited ? false : 0 === $this->number_of_tickets_left;
	}

	/**
	 * Returns true if there is unlimited amount of free tickets for an event
	 *
	 * @return boolean
	 * @since  2.0.0
	 */
	public function without_limit() {
		return $this->unlimited;
	}


}
