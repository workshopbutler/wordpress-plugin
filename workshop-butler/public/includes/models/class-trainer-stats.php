<?php
/**
 * This class contains trainer's statistics
 *
 * @package WorkshopButler
 * @since 2.7.0
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-combined-workshop-stats.php';

/**
 * Trainer's statistics
 *
 * @package WorkshopButler
 * @since 2.7.0
 */
class Trainer_Stats {

	/**
	 * Creates a new Trainer_Stats object
	 *
	 * @param object $json JSON value.
	 *
	 * @return Trainer_Stats
	 */
	static function from_json( $json ) {
		return new Trainer_Stats(
			Combined_Workshop_Stats::from_json( $json->recent ),
			Combined_Workshop_Stats::from_json( $json->total ),
			$json->years_of_experience
		);
	}

	/**
	 * All-time workshop statistics.
	 *
	 * @var Combined_Workshop_Stats $total
	 * @since 2.7.0
	 */
	public $total;

	/**
	 * Statistics for the last 12 months.
	 *
	 * @var Combined_Workshop_Stats $recent
	 * @since 2.7.0
	 */
	public $recent;

	/**
	 * Years of experience for a specific brand.
	 *
	 * @var int|null $years_of_experience
	 * @since 2.7.0
	 */
	public $years_of_experience;

	/**
	 * Trainer_Stats constructor.
	 *
	 * @param Combined_Workshop_Stats $recent Statistics for the last 12 months.
	 * @param Combined_Workshop_Stats $total All-time statistics.
	 * @param int|null                $years_of_experience Years of experience for a specific brand.
	 */
	public function __construct( $recent, $total, $years_of_experience ) {
		$this->recent              = $recent;
		$this->total               = $total;
		$this->years_of_experience = $years_of_experience;
	}
}
