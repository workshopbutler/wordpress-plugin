<?php
/**
 * The file that defines the Ticket class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-field.php';

/**
 * Form field with tickets' info, where visitors can select a ticket of their choice
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Ticket extends Field {
	/**
	 * True if a sales tax is not included in the prices
	 *
	 * @var boolean $excluded_tax
	 * @since 2.0.0
	 */
	public $excluded_tax;

	/**
	 * Tickets
	 *
	 * @var Tickets $tickets
	 * @since 2.0.0
	 */
	public $tickets;

	/**
	 * Ticket constructor
	 *
	 * @param object       $json_data JSON field data.
	 * @param Paid_Tickets $tickets Available event's tickets.
	 */
	public function __construct( $json_data, $tickets ) {
		parent::__construct( $json_data );
		$this->tickets      = $tickets;
		$this->excluded_tax = $tickets->excluded_tax;
	}
}
