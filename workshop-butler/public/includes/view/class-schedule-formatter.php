<?php
/**
 * The file that defines the Schedule formatter class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class-date-formatter.php';


/**
 * Formats a schedule
 * @since 2.0.0
 */
class Schedule_Formatter {
    
    /**
     * @param Schedule     $schedule Schedule to format
     * @param string|null  $type Additional format type
     *
     * @since 2.0.0
     * @return string
     */
    static function format($schedule, $type) {
        $type = $type ? $type : 'full_long';
        $withTime = $schedule->timezone !== null;
        switch ($type) {
            case 'start_long': return Date_Formatter::format($schedule->start, $withTime);
            case 'start_short': return Date_Formatter::format($schedule->start);
            case 'end_long': return Date_Formatter::format($schedule->end, $withTime);
            case 'end_short': return Date_Formatter::format($schedule->end);
            case 'timezone_long':
                return $schedule->timezone ? $schedule->timezone : '';
            case 'timezone_short':
                return $schedule->timezone ? $schedule->start->format('T') : '';
            case 'full_short': return self::format_full_date($schedule);
            case 'full_long':
                if (!$schedule->timezone) {
                    return self::format_full_date($schedule);
                } else {
                    return self::format_full_date($schedule);
                }
            default: return '';
        }
    }
    
    /**
     * @param Schedule $schedule Schedule to format
     * @return string
     * @since 2.0.0
     */
    protected static function format_full_date($schedule) {
        if ($schedule->at_one_day()) {
            return Date_Formatter::format($schedule->start);
        } else if ($schedule->start->format('Y') != $schedule->end->format('Y')
            && $schedule->start->format('m') != $schedule->end->format('m')) {
            return Date_Formatter::format($schedule->start) . ' — ' . Date_Formatter::format($schedule->end);
        } else {
            return self::format_same_month_interval($schedule->start, $schedule->end);
        }
    }
    
    /**
     * Formats a date interval for the same month in a localised manner
     *
     * For example, the interval 19-20 April 2018 will be
     *  - April 19-20, 2018 in US
     *  - 19-20 April 2018 in Germany
     *
     * @param DateTime $start Start of the workshop
     * @param DateTime $end   End of the workshop
     * @return string
     * @since 2.0.0
     */
    protected static function format_same_month_interval($start, $end) {
        global $wp_locale;
    
        if (Date_Formatter::is_textual_month()) {
            $numericDays = $start->format('d') . '-' . $end->format('d');
            $withoutZeroDays = $start->format('j') . '-' . $end->format('j');
    
            if ( ( !empty( $wp_locale->month ) ) ) {
                $textualMonth = $wp_locale->get_month( date( 'm', $start->getTimestamp() ) );
                $textualMonthAbbr = $wp_locale->get_month_abbrev( $textualMonth );
            } else {
                $textualMonth = '';
                $textualMonthAbbr = '';
            }
            $longYear = $start->format('Y');
            $shortYear = $start->format('y');
    
            $date_format = Date_Formatter::get_date_format($start);
            $date = str_replace('d', $numericDays, $date_format);
            $date = str_replace('j', $withoutZeroDays, $date);
            $date = str_replace('Y', $longYear, $date);
            $date = str_replace('y', $shortYear, $date);
            if (strpos($date_format, 'F') !== false) {
                $date = str_replace('F', $textualMonth, $date);
            } else {
                $date = str_replace( 'M', $textualMonthAbbr, $date );
            }
        } else {
            $date = Date_Formatter::format($start) . '—' . Date_Formatter::format($end);
        }
        
        
        return trim($date, '.,-/ ');
    }
}
