<?php
/**
 * The file that defines the Schedule formatter class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class-date-formatter.php';

/**
 * Formats a schedule
 *
 * @since 2.0.0
 */
class Schedule_Formatter {

	/**
	 * Formats the schedule
	 *
	 * @param Schedule    $schedule Schedule to format.
	 * @param string|null $type Additional format type.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public static function format( $schedule, $type ) {
		$type      = $type ? $type : 'full_long';
		$with_time = null !== $schedule->timezone;
		switch ( $type ) {
			case 'start_long':
				return Date_Formatter::format( $schedule->start, $with_time );
			case 'start_short':
				return Date_Formatter::format( $schedule->start );
			case 'end_long':
				return Date_Formatter::format( $schedule->end, $with_time );
			case 'end_short':
				return Date_Formatter::format( $schedule->end );
			case 'timezone_long':
				return $schedule->timezone ? $schedule->timezone : '';
			case 'timezone_short':
				return self::format_timezone( $schedule );
			case 'time':
				return self::format_time( $schedule );
			case 'full_short':
				return self::format_full_date( $schedule, false );
			case 'full_long':
				if ( ! $schedule->timezone ) {
					return self::format_full_date( $schedule, false );
				} else {
					return self::format_full_date( $schedule, true );
				}
			default:
				return '';
		}
	}

	/**
	 * Produces a formatted timezone for the schedule
	 *
	 * @param Schedule $schedule Schedule to format.
	 *
	 * @return string
	 * @since 2.11.0
	 */
	protected static function format_timezone( $schedule ) {
		$start = clone $schedule->start;
		if ( $schedule->timezone ) {
			$start->setTimezone( $schedule->default_timezone() );
		}
		$abbreviation = $start->format( 'T' );
		if ( 0 === strpos( $abbreviation, '-' ) || 0 === strpos( $abbreviation, '+' ) ) {
			$abbreviation = 'GMT' . $abbreviation;
		}

		return $schedule->timezone ? $abbreviation : '';
	}

	/**
	 * Produces a formatted time for the schedule
	 *
	 * @param Schedule $schedule Schedule to format.
	 *
	 * @return string
	 * @since 2.11.0
	 */
	protected static function format_time( $schedule ) {
		if ( $schedule->at_one_day() ) {
			return Date_Formatter::format_one_day_time( $schedule->start, $schedule->end );
		} else {
			return Date_Formatter::format_time( $schedule->start );
		}
	}

	/**
	 * Produces a formatted schedule in a full-date format
	 *
	 * @param Schedule $schedule Schedule to format.
	 * @param boolean  $with_time When true, the time is added.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected static function format_full_date( $schedule, $with_time ) {
		if ( $schedule->at_one_day() ) {
			if ( $with_time ) {
				return Date_Formatter::format_one_day( $schedule->start, $schedule->end );
			} else {
				return Date_Formatter::format( $schedule->start );
			}
		} elseif ( $schedule->start->format( 'Y' ) !== $schedule->end->format( 'Y' )
				|| $schedule->start->format( 'm' ) !== $schedule->end->format( 'm' ) ) {
			return Date_Formatter::format( $schedule->start ) . ' — ' . Date_Formatter::format( $schedule->end );
		} else {
			return self::format_same_month_interval( $schedule->start, $schedule->end );
		}
	}

	/**
	 * Formats a date interval for the same month in a localised manner
	 *
	 * For example, the interval 19-20 April 2018 will be
	 *  - April 19-20, 2018 in US
	 *  - 19-20 April 2018 in Germany
	 *
	 * @param \DateTime $start Start of the workshop.
	 * @param \DateTime $end End of the workshop.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected static function format_same_month_interval( $start, $end ) {
		global $wp_locale;

		if ( Date_Formatter::is_textual_month() ) {
			if ( ( ! empty( $wp_locale->month ) ) ) {
				$textual_month      = $wp_locale->get_month( date( 'm', $start->getTimestamp() ) );
				$textual_month_abbr = $wp_locale->get_month_abbrev( $textual_month );
			} else {
				$textual_month      = '';
				$textual_month_abbr = '';
			}

			$date_format = $date = Date_Formatter::get_date_format( $start );
			if ( strpos( $date_format, 'dS' ) !== false ) {
				$date = str_replace( 'dS', $start->format( 'dS' ) . '—' . $end->format( 'dS' ), $date );
			} else {
				$date = str_replace( 'd', $start->format( 'd' ) . '—' . $end->format( 'd' ), $date );
			}
			if ( strpos( $date_format, 'jS' ) !== false ) {
				$date = str_replace( 'jS', $start->format( 'jS' ) . '—' . $end->format( 'jS' ), $date );
			} else {
				$date = str_replace( 'j', $start->format( 'j' ) . '—' . $end->format( 'j' ), $date );
			}
			$date = str_replace( 'Y', $start->format( 'Y' ), $date );
			$date = str_replace( 'y', $start->format( 'y' ), $date );
			if ( strpos( $date_format, 'F' ) !== false ) {
				$date = str_replace( 'F', $textual_month, $date );
			} else {
				$date = str_replace( 'M', $textual_month_abbr, $date );
			}
		} else {
			$date = Date_Formatter::format( $start ) . '—' . Date_Formatter::format( $end );
		}

		return trim( $date, '.,-/ ' );
	}
}
