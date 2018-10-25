<?php
/**
 * The file that defines Ticket_type class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents a general ticket type
 *
 * @package WorkshopButler
 */
abstract class Ticket_Type {
	/**
	 * Number of tickets on sale
	 *
	 * @since  2.0.0
	 * @var    int $number_of_tickets_left Number of tickets on sale
	 */
	public $number_of_tickets_left;

	/**
	 * Returns true if no more seats left
	 *
	 * @return boolean
	 */
	abstract public function sold_out();

	/**
	 * Returns true if there is no limitation for a number of tickets
	 */
	abstract public function without_limit();
}
