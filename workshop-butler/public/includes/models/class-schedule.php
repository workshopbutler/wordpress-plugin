<?php
/**
 * The file that defines the schedule class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

/**
 * Represents a schedule for a workshop
 */
class Schedule {
	/**
	 * @since 2.0.0
	 * @var DateTime $start The start date and time of a workshop
	 */
	public $start;

	/**
	 * @since 2.0.0
	 * @var DateTime $end The end date and time of a workshop
	 */
	public $end;

	/**
	 * @since 2.0.0
	 * @var string|null $timezone Timezone of a workshop
	 */
	public $timezone;

	/**
	 * Initialises a new schedule
	 *
	 * @param object $json_data JSON data from Workshop Butler API
	 */
	public function __construct( $json_data ) {
		if ( $json_data->timezone ) {
			$this->timezone = $json_data->timezone;
		} else {
			$this->timezone = null;
		}
		$this->start = new DateTime( $json_data->start, $this->default_timezone() );
		$this->end   = new DateTime( $json_data->end, $this->default_timezone() );
	}

	/**
	 * Returns true if the event has ended
	 *
	 * @since 2.0.0
	 */
	public function ended() {
		$now = new DateTime( 'now', $this->default_timezone() );
		return $this->end < $now;
	}

	/**
	 * Returns the default timezone if none exists
	 *
	 * @since 2.0.0
	 */
	public function default_timezone() {
		return $this->timezone ? new DateTimeZone( $this->timezone ) : new DateTimeZone( 'UTC' );
	}

	/**
	 * Returns true if the event is at one day
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function at_one_day() {
		return $this->start->format( 'yyyy-MM-dd' ) == $this->end->format( 'yyyy-MM-dd' );
	}

}
