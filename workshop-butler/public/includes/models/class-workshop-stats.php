<?php
/**
 * This file contains Workshop_Stats class
 *
 * @since 2.7.0
 * @package WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-votes.php';

/**
 * Describes a workshop's statistics
 *
 * @package WorkshopButler
 * @since 2.7.0
 */
class Workshop_Stats {

	/**
	 * Creates a Workshop_Stats object
	 *
	 * @param object $json JSON value.
	 *
	 * @return Workshop_Stats
	 */
	static function from_json( $json ) {
		return new Workshop_Stats( $json->evaluations, $json->median, $json->nps, $json->rating, new Votes( $json->votes ) );
	}

	/**
	 * Number of evaluations.
	 *
	 * @var int $evaluations
	 * @since 2.7.0
	 */
	public $evaluations;

	/**
	 * Median
	 *
	 * @var float $median
	 * @since 2.7.0
	 */
	public $median;

	/**
	 * NPS in percents
	 *
	 * @var float $nps
	 * @since 2.7.0
	 */
	public $nps;

	/**
	 * Average rating.
	 *
	 * @var float $rating
	 * @since 2.7.0
	 */
	public $rating;

	/**
	 * Raw attendees' votes.
	 *
	 * @var Votes $votes
	 * @since 2.7.0
	 */
	public $votes;

	public function get_rounded_rating() {
		return round( $this->rating, 1, PHP_ROUND_HALF_DOWN );
	}

	/**
	 * Workshop_Stats constructor.
	 *
	 * @param int   $evaluations Number of evaluations.
	 * @param float $median Median.
	 * @param float $nps NPS in percents.
	 * @param float $rating Rating.
	 * @param Votes $votes Raw attendees' votes.
	 */
	public function __construct( $evaluations, $median, $nps, $rating, $votes ) {
		$this->evaluations = $evaluations;
		$this->median      = $median;
		$this->nps         = $nps;
		$this->rating      = $rating;
		$this->votes       = $votes;
	}
}
