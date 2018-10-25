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
	 * @param string|null $type     Additional format type.
	 *
	 * @since 2.0.0
	 * @return string
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
				return $schedule->timezone ? $schedule->start->format( 'T' ) : '';
			case 'full_short':
				return self::format_full_date( $schedule );
			case 'full_long':
				if ( ! $schedule->timezone ) {
					return self::format_full_date( $schedule );
				} else {
					return self::format_full_date( $schedule );
				}
			default:
				return '';
		}
	}

	/**
	 * @param Schedule $schedule Schedule to format
	 * @return string
	 * @since 2.0.0
	 */
	protected static function format_full_date( $schedule ) {
		if ( $schedule->at_one_day() ) {
			return Date_Formatter::format( $schedule->start );
		} elseif ( $schedule->start->format( 'Y' ) != $schedule->end->format( 'Y' )
			&& $schedule->start->format( 'm' ) != $schedule->end->format( 'm' ) ) {
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
	 * @param \DateTime $end   End of the workshop.
	 * @return string
	 * @since 2.0.0
	 */
	protected static function format_same_month_interval( $start, $end ) {
		global $wp_locale;

		if ( Date_Formatter::is_textual_month() ) {
			$numeric_days     = $start->format( 'd' ) . '-' . $end->format( 'd' );
			$without_zero_ays = $start->format( 'j' ) . '-' . $end->format( 'j' );

			if ( (! empty( $wp_locale->month )) ) {
				$textual_month      = $wp_locale->get_month( date( 'm', $start->getTimestamp() ) );
				$textual_month_abbr = $wp_locale->get_month_abbrev( $textual_month );
			} else {
				$textual_month      = '';
				$textual_month_abbr = '';
			}
			$long_year  = $start->format( 'Y' );
			$short_year = $start->format( 'y' );

			$date_format = Date_Formatter::get_date_format( $start );
			$date        = str_replace( 'd', $numeric_days, $date_format );
			$date        = str_replace( 'j', $without_zero_ays, $date );
			$date        = str_replace( 'Y', $long_year, $date );
			$date        = str_replace( 'y', $short_year, $date );
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
