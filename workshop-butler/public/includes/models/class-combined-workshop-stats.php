<?php
/**
 * This file contains Combined_Workshop_Stats class
 *
 * @package WorkshopButler
 * @since 2.7.0
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-workshop-stats.php';

/**
 * Describes statistics for both public and private workshops
 *
 * @since 2.7.0
 * @package WorkshopButler
 */
class Combined_Workshop_Stats {

	/**
	 * Creates a new Combined_Workshop_Stats object.
	 *
	 * @param object $json JSON value.
	 *
	 * @return Combined_Workshop_Stats
	 */
	static function from_json( $json ) {
		return new Combined_Workshop_Stats(
			Workshop_Stats::from_json( $json->public ),
			Workshop_Stats::from_json( $json->private ),
			$json->workshops
		);
	}

	/**
	 * Total number of workshops.
	 *
	 * @var int $total
	 * @since 2.7.0
	 */
	public $total;

	/**
	 * Statistics for public workshops.
	 *
	 * @var Workshop_Stats $public_stats
	 * @since 2.7.0
	 */
	public $public_stats;

	/**
	 * Statistics for private workshops.
	 *
	 * @var Workshop_Stats $private_stats
	 * @since 2.7.0
	 */
	public $private_stats;

	/**
	 * Combined_Workshop_Stats constructor.
	 *
	 * @param Workshop_Stats $public_stats Statistics for public workshops.
	 * @param Workshop_Stats $private_stats Statistics for private workshops.
	 * @param int            $total Total number of workshops.
	 */
	public function __construct( $public_stats, $private_stats, $total ) {
		$this->public_stats  = $public_stats;
		$this->private_stats = $private_stats;
		$this->total         = $total;
	}
}
