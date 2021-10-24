<?php
/**
 * This file contains Ticket_Section class.
 *
 * @package WorkshopButler
 * @since 2.7.0
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-section.php';

/**
 * Represents a ticket section on the form
 *
 * @package WorkshopButler
 * @since 2.7.0
 */
class Ticket_Section extends Section {

	/**
	 * Unique ID of the section
	 *
	 * @var string $id
	 * @since 2.7.0
	 */
	static public $section_id = 'ticket';

	/**
	 * Name of promo code field
	 *
	 * @var string $promo
	 * @since 2.7.0
	 */
	static public $promo = 'promo_code';

	/**
	 * True if the section contains 'promo code' field
	 *
	 * @var boolean $with_promo
	 * @since 2.7.0
	 */
	public $with_promo;

	/**
	 * True if the tax is excluded from the price of tickets.
	 *
	 * @var boolean $excluded_tax
	 * @since 2.7.0
	 */
	public $excluded_tax;

	/**
	 * Size of tax
	 *
	 * @var float|null $tax
	 * @since 2.7.0
	 */
	public $tax_rate;

	/**
	 * Related event
	 *
	 * @var Event $event
	 * @since 2.7.0
	 */
	protected $event;

	/**
	 * Ticket_Section constructor.
	 *
	 * @param string|null $name Name of the section.
	 * @param object[]    $fields Section's fields.
	 * @param Event       $event Related event.
	 */
	public function __construct( $name, $fields, $event ) {
		parent::__construct( Ticket_Section::$section_id, $name, $fields, $event );
		$with_promo = false;
		foreach ( $fields as $field ) {
			if ( Ticket_Section::$promo === $field->name ) {
				$with_promo = true;
				break;
			}
		}
		$this->with_promo   = $with_promo;
		$this->event        = $event;
		$this->excluded_tax = $event->tickets->excluded_tax;
		$this->tax_rate          = $event->tickets->tax_rate;
	}
}
