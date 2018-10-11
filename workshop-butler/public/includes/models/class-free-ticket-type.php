<?php
/**
 * The file that defines Free_Ticket_type class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-type.php';

/**
 * This class represents a free ticket type in a Workshop Butler event
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Free_Ticket_Type extends Ticket_Type {

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
	 * If true, there is unlimited amount of free tickets for an event
	 *
	 * @since  2.0.0
	 * @var    boolean $unlimited If true, there is unlimited amount of free tickets for an event
	 */
	private $unlimited;

	/**
	 * If true, all free tickets are sold out
	 *
	 * @since  2.0.0
	 * @var    boolean $sold_out If true, all free tickets are sold out
	 */
	private $sold_out;

	/**
	 * Creates a new paid ticket type from JSON
	 *
	 * @param $json_data object JSON for a ticket type
	 */
	public function __construct($json_data ) {
		$this->number_of_tickets      = $json_data->amount;
		$this->number_of_tickets_left = $json_data->left;
		$this->start                  = new DateTime( $json_data->start );
		$this->end                    = new DateTime( $json_data->end );
		$this->unlimited              = $json_data->unlimited;
		$this->sold_out               = $json_data->state->sold_out;
	}

	/**
	 * Returns true if no more seats left
	 *
	 * @since  2.0.0
	 * @return boolean
	 */
	public function sold_out() {
		return $this->sold_out;
	}

	/**
	 * Returns true if there is unlimited amount of free tickets for an event
	 *
	 * @since  2.0.0
	 * @return boolean
	 */
	public function without_limit() {
		return $this->unlimited;
	}


}
