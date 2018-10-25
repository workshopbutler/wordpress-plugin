<?php
/**
 * The file that defines the statistics class which a trainer can have
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Statistics class contains a trainer statistics of one type
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Statistics {
	/**
	 * Number of evaluations provided by the students
	 *
	 * @since 2.0.0
	 * @var int $number_of_evaluations
	 */
	public $number_of_evaluations;

	/**
	 * Median of all evaluations
	 *
	 * @since 2.0.0
	 * @var float $median
	 */
	public $median;

	/**
	 * NPS calculated from evaluations
	 *
	 * @since 2.0.0
	 * @var float $nps
	 */
	public $nps;

	/**
	 * Rating calculated from evaluations
	 *
	 * @since 2.0.0
	 * @var float $rating
	 */
	public $rating;

	/**
	 * Statistics constructor
	 *
	 * @param int   $number_of_evaluations Number of evaluations.
	 * @param float $median                Median.
	 * @param float $nps                   NPS.
	 * @param float $rating                Rating.
	 */
	public function __construct( $number_of_evaluations, $median, $nps, $rating ) {
		$this->number_of_evaluations = $number_of_evaluations;
		$this->median                = $median;
		$this->nps                   = $nps;
		$this->rating                = $rating;
	}
}
