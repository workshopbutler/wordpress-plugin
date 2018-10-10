<?php
/**
 * The file that defines the statistics class which a trainer can have
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

/**
 * Statistics class contains a trainer statistics of one type
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Statistics {
	public $number_of_evaluations;
	public $median;
	public $nps;
	public $rating;

	public function __construct( $number_of_evaluations, $median, $nps, $rating ) {
		$this->number_of_evaluations = $number_of_evaluations;
		$this->median                = $median;
		$this->nps                   = $nps;
		$this->rating                = $rating;
	}
}
