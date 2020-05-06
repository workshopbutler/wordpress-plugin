<?php
/**
 * The file that defines the schedule class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents a schedule for a workshop
 */
class Schedule {
	/**
	 * The start date and time of a workshop
	 *
	 * @since 2.0.0
	 * @var \DateTime $start
	 */
	public $start;

	/**
	 * The end date and time of a workshop
	 *
	 * @since 2.0.0
	 * @var \DateTime $end
	 */
	public $end;

	/**
	 * Timezone of a workshop
	 *
	 * @since 2.0.0
	 * @var string|null $timezone
	 */
	public $timezone;

	/**
	 * Initialises a new schedule
	 *
	 * @param object $json_data JSON data from Workshop Butler API.
	 */
	public function __construct( $json_data ) {
		if ( $json_data->timezone ) {
			$this->timezone = $json_data->timezone;
		} else {
			$this->timezone = null;
		}
		$this->start = new \DateTime( $json_data->start );
		$this->end   = new \DateTime( $json_data->end );
		if ( $this->timezone ) {
			$timezone = new \DateTimeZone( $this->timezone );
			$this->start->setTimezone( $timezone );
			$this->end->setTimezone( $timezone );
		}
	}

	/**
	 * Returns true if the event has ended
	 *
	 * @since 2.0.0
	 */
	public function ended() {
		$now = new \DateTime( 'now', $this->default_timezone() );
		$end = clone $this->end;
		$end->setTimezone( $this->default_timezone() );

		return $end < $now;
	}

	/**
	 * Returns the default timezone if none exists
	 *
	 * @since 2.0.0
	 */
	public function default_timezone() {
		return $this->timezone ? new \DateTimeZone( $this->timezone ) : new \DateTimeZone( 'UTC' );
	}

	/**
	 * Returns true if the event is at one day
	 *
	 * @return boolean
	 * @since 2.0.0
	 */
	public function at_one_day() {
		return $this->start->format( 'y-M-d' ) === $this->end->format( 'y-M-d' );
	}

}
