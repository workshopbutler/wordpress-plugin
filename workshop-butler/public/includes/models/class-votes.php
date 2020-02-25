<?php
/**
 * This file contains Votes class
 *
 * @package WorkshopButler
 * @since 2.7.0
 */

namespace WorkshopButler;

/**
 * Represents the set of votes for one object (workshop, for example)
 *
 * @package WorkshopButler
 * @since 2.7.0
 */
class Votes {

	/**
	 * Number of voters, who gave '0'
	 *
	 * @var int $vote0
	 * @since 2.7.0
	 */
	public $vote0;

	/**
	 * Number of voters, who gave '1'
	 *
	 * @var int $vote1
	 * @since 2.7.0
	 */
	public $vote1;

	/**
	 * Number of voters, who gave '2'
	 *
	 * @var int $vote2
	 * @since 2.7.0
	 */
	public $vote2;

	/**
	 * Number of voters, who gave '3'
	 *
	 * @var int $vote3
	 * @since 2.7.0
	 */
	public $vote3;

	/**
	 * Number of voters, who gave '4'
	 *
	 * @var int $vote4
	 * @since 2.7.0
	 */
	public $vote4;

	/**
	 * Number of voters, who gave '5'
	 *
	 * @var int $vote5
	 * @since 2.7.0
	 */
	public $vote5;

	/**
	 * Number of voters, who gave '6'
	 *
	 * @var int $vote6
	 * @since 2.7.0
	 */
	public $vote6;

	/**
	 * Number of voters, who gave '7'
	 *
	 * @var int $vote7
	 * @since 2.7.0
	 */
	public $vote7;

	/**
	 * Number of voters, who gave '8'
	 *
	 * @var int $vote8
	 * @since 2.7.0
	 */
	public $vote8;

	/**
	 * Number of voters, who gave '9'
	 *
	 * @var int $vote9
	 * @since 2.7.0
	 */
	public $vote9;

	/**
	 * Number of voters, who gave '10'
	 *
	 * @var int $vote10
	 * @since 2.7.0
	 */
	public $vote10;

	/**
	 * Votes constructor.
	 *
	 * @param array $votes Array of votes.
	 */
	function __construct( $votes ) {
		$this->vote0  = $this->get_value( '0', $votes );
		$this->vote1  = $this->get_value( '1', $votes );
		$this->vote2  = $this->get_value( '2', $votes );
		$this->vote3  = $this->get_value( '3', $votes );
		$this->vote4  = $this->get_value( '4', $votes );
		$this->vote5  = $this->get_value( '5', $votes );
		$this->vote6  = $this->get_value( '6', $votes );
		$this->vote7  = $this->get_value( '7', $votes );
		$this->vote8  = $this->get_value( '8', $votes );
		$this->vote9  = $this->get_value( '9', $votes );
		$this->vote10 = $this->get_value( '10', $votes );
	}

	/**
	 * Return the value of particular index position.
	 *
	 * @param string $id Array position.
	 * @param array  $votes Array of votes.
	 *
	 * @return int
	 */
	private function get_value( $id, $votes ) {
		if ( is_array( $votes ) && array_key_exists( $id, $votes ) ) {
			return intval( $votes[ $id ] );
		} else {
			return 0;
		}
	}
}
