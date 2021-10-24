<?php
/**
 * This file contains Paid_Tickets class
 *
 * @package WorkshopButler
 * @since 2.7.0
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-paid-ticket-type.php';

/**
 * Contains the logic to work with paid ticket types
 *
 * @package WorkshopButler
 * @since 2.7.0
 */
class Paid_Tickets {

	/**
	 * Creates Paid_Tickets object from JSON
	 *
	 * @param object $json JSON to convert.
	 *
	 * @return Paid_Tickets
	 * @since 2.7.0
	 */
	static function from_json( $json ) {
		$types = array();
		foreach ( $json->types as $type ) {
			array_push( $types, Paid_Ticket_Type::from_json( $type ) );
		}
		return new Paid_Tickets( $types, $json->tax_excluded, $json->tax_rate, $json->tax_validation );
	}

	/**
	 * True when a sales tax is NOT included in the price
	 *
	 * @since 2.7.0
	 * @var boolean $excluded_tax
	 */
	public $excluded_tax;

	/**
	 * True when VAT validation is allowed
	 *
	 * @since 3.1.0
	 * @var boolean $validate_tax
	 */
	public $validate_tax;

	/**
	 * Tax size (in percents)
	 *
	 * @since 3.1.0
	 * @var number|null $tax
	 */
	public $tax_rate;

	/**
	 * Available ticket types for a workshop
	 *
	 * @since 2.7.0
	 * @var Paid_Ticket_Type[] $types
	 */
	public $types;

	/**
	 * Returns the id of the first active paid ticket if it exists
	 *
	 * @since 2.7.0
	 * @var string|null $active_ticket_id
	 */
	public $active_ticket_id;

	/**
	 * Creates the object.
	 *
	 * @param Paid_Ticket_Type[] $types Types of paid tickets.
	 * @param boolean            $excluded_tax True if the tax is excluded.
	 * @param number|null        $tax Size of the tax.
	 */
	function __construct( $types, $excluded_tax, $tax_rate, $validate_tax ) {
		$this->types            = $types;
		$this->excluded_tax     = $excluded_tax;
		$this->validate_tax     = $validate_tax;
		$this->tax_rate         = $tax_rate ? $tax_rate : null;
		$this->active_ticket_id = $this->get_active_ticket_id();
	}

	/**
	 * Returns the list of paid ticket types
	 *
	 * @return Paid_Ticket_Type[]
	 * @since 3.0.0
	 */
	public function get_types() {
		return $this->types;
	}

	/**
	 * Returns only active ticket types.
	 *
	 * @return Paid_Ticket_Type[]
	 */
	function active() {
		return array_filter(
			$this->types,
			/**
			 * Filter function.
			 *
			 * @param Paid_Ticket_Type $type
			 *
			 * @return boolean
			 */
			function ( $type ) {
				return $type->active();
			}
		);
	}

	/**
	 * Returns only ended ticket types.
	 *
	 * @return Paid_Ticket_Type[]
	 */
	function ended() {
		return array_filter(
			$this->types,
			/**
			 * Filter function.
			 *
			 * @param Paid_Ticket_Type $type
			 *
			 * @return boolean
			 */
			function ( $type ) {
				return $type->ended();
			}
		);
	}

	/**
	 * Returns only not-started ticket types.
	 *
	 * @return Paid_Ticket_Type[]
	 */
	function in_future() {
		return array_filter(
			$this->types,
			/**
			 * Filter function.
			 *
			 * @param Paid_Ticket_Type $type
			 *
			 * @return boolean
			 */
			function ( $type ) {
				return $type->in_future();
			}
		);
	}

	/**
	 * Returns first active paid ticket type.
	 *
	 * @return Paid_Ticket_Type|null
	 * @since 2.7.0
	 */
	function get_first_active_ticket() {
		if ( $this->active_ticket_id ) {
			$filtered = array_filter(
				$this->types,
				/**
				 * Filter function.
				 *
				 * @param Paid_Ticket_Type $type
				 *
				 * @return boolean
				 */
				function ( $type ) {
					return $type->id === $this->active_ticket_id;
				}
			);

			return empty( $filtered ) ? null : current( $filtered );
		} else {
			$active = $this->active();
			return empty( $active ) ? null : current( $active );
		}
	}

	/**
	 * Returns the ID of paid ticket type.
	 *
	 * @return string|null
	 * @since 2.7.0
	 */
	protected function get_active_ticket_id() {
		$active = $this->get_first_active_ticket();
		if ( $active ) {
			return $active->id;
		} else {
			return null;
		}
	}
}
