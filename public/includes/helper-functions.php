<?php
/**
 * Created by IntelliJ IDEA.
 * User: sery0ga
 * Date: 04/05/2018
 * Time: 18:48
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function wsb_get_full_url() {
    $base_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'];
    $url      = $base_url . $_SERVER["REQUEST_URI"];
    
    return $url;
}

function wsb_get_country_name($countryCode) {
    
    $countries = wsb_get_countries();
    
    if(array_key_exists($countryCode, $countries)) {
        return $countries[$countryCode];
    } else {
        return "";
    }
}

function wsb_get_countries() {
    return array("AF" => __("Afghanistan", 'wsbintegration'),
                 "AL" => __("Albania", 'wsbintegration'),
                 "DZ" => __("Algeria", 'wsbintegration'),
                 "AS" => __("American Samoa", 'wsbintegration'),
                 "AD" => __("Andorra", 'wsbintegration'),
                 "AO" => __("Angola", 'wsbintegration'),
                 "AI" => __("Anguilla", 'wsbintegration'),
                 "AG" => __("Antigua and Barbuda", 'wsbintegration'),
                 "AR" => __("Argentina", 'wsbintegration'),
                 "AM" => __("Armenia", 'wsbintegration'),
                 "AW" => __("Aruba", 'wsbintegration'),
                 "AC" => __("Ascension Island", 'wsbintegration'),
                 "AU" => __("Australia", 'wsbintegration'),
                 "AT" => __("Austria", 'wsbintegration'),
                 "AZ" => __("Azerbaijan", 'wsbintegration'),
                 "BS" => __("Bahamas", 'wsbintegration'),
                 "BH" => __("Bahrain", 'wsbintegration'),
                 "BD" => __("Bangladesh", 'wsbintegration'),
                 "BB" => __("Barbados", 'wsbintegration'),
                 "BY" => __("Belarus", 'wsbintegration'),
                 "BE" => __("Belgium", 'wsbintegration'),
                 "BZ" => __("Belize", 'wsbintegration'),
                 "BJ" => __("Benin", 'wsbintegration'),
                 "BM" => __("Bermuda", 'wsbintegration'),
                 "BT" => __("Bhutan", 'wsbintegration'),
                 "BO" => __("Bolivia", 'wsbintegration'),
                 "BQ" => __("Bonaire, Saint Eustatius and Saba", 'wsbintegration'),
                 "BA" => __("Bosnia and Herzegovina", 'wsbintegration'),
                 "BW" => __("Botswana", 'wsbintegration'),
                 "BV" => __("Bouvet Island", 'wsbintegration'),
                 "BR" => __("Brazil", 'wsbintegration'),
                 "IO" => __("British Indian Ocean Territory", 'wsbintegration'),
                 "VG" => __("British Virgin Islands", 'wsbintegration'),
                 "BN" => __("Brunei", 'wsbintegration'),
                 "BG" => __("Bulgaria", 'wsbintegration'),
                 "BF" => __("Burkina Faso", 'wsbintegration'),
                 "BI" => __("Burundi", 'wsbintegration'),
                 "KH" => __("Cambodia", 'wsbintegration'),
                 "CM" => __("Cameroon", 'wsbintegration'),
                 "CA" => __("Canada", 'wsbintegration'),
                 "IC" => __("Canary Islands", 'wsbintegration'),
                 "CT" => __("Canton and Enderbury Islands", 'wsbintegration'),
                 "CV" => __("Cape Verde", 'wsbintegration'),
                 "KY" => __("Cayman Islands", 'wsbintegration'),
                 "CF" => __("Central African Republic", 'wsbintegration'),
                 "EA" => __("Ceuta and Melilla", 'wsbintegration'),
                 "TD" => __("Chad", 'wsbintegration'),
                 "CL" => __("Chile", 'wsbintegration'),
                 "CN" => __("China", 'wsbintegration'),
                 "CX" => __("Christmas Island", 'wsbintegration'),
                 "CP" => __("Clipperton Island", 'wsbintegration'),
                 "CC" => __("Cocos Keeling Islands", 'wsbintegration'),
                 "CO" => __("Colombia", 'wsbintegration'),
                 "KM" => __("Comoros", 'wsbintegration'),
                 "CG" => __("Congo - Brazzaville", 'wsbintegration'),
                 "CD" => __("Congo - Kinshasa", 'wsbintegration'),
                 "CK" => __("Cook Islands", 'wsbintegration'),
                 "CR" => __("Costa Rica", 'wsbintegration'),
                 "HR" => __("Croatia", 'wsbintegration'),
                 "CU" => __("Cuba", 'wsbintegration'),
                 "CW" => __("Curaçao", 'wsbintegration'),
                 "CY" => __("Cyprus", 'wsbintegration'),
                 "CZ" => __("Czech Republic", 'wsbintegration'),
                 "CI" => __("Côte d’Ivoire", 'wsbintegration'),
                 "DK" => __("Denmark", 'wsbintegration'),
                 "DG" => __("Diego Garcia", 'wsbintegration'),
                 "DJ" => __("Djibouti", 'wsbintegration'),
                 "DM" => __("Dominica", 'wsbintegration'),
                 "DO" => __("Dominican Republic", 'wsbintegration'),
                 "NQ" => __("Dronning Maud Land", 'wsbintegration'),
                 "EC" => __("Ecuador", 'wsbintegration'),
                 "EG" => __("Egypt", 'wsbintegration'),
                 "SV" => __("El Salvador", 'wsbintegration'),
                 "GQ" => __("Equatorial Guinea", 'wsbintegration'),
                 "ER" => __("Eritrea", 'wsbintegration'),
                 "EE" => __("Estonia", 'wsbintegration'),
                 "ET" => __("Ethiopia", 'wsbintegration'),
                 "EU" => __("European Union", 'wsbintegration'),
                 "FK" => __("Falkland Islands", 'wsbintegration'),
                 "FJ" => __("Fiji", 'wsbintegration'),
                 "FI" => __("Finland", 'wsbintegration'),
                 "FR" => __("France", 'wsbintegration'),
                 "GF" => __("French Guiana", 'wsbintegration'),
                 "PF" => __("French Polynesia", 'wsbintegration'),
                 "GA" => __("Gabon", 'wsbintegration'),
                 "GM" => __("Gambia", 'wsbintegration'),
                 "GE" => __("Georgia", 'wsbintegration'),
                 "DE" => __("Germany", 'wsbintegration'),
                 "GH" => __("Ghana", 'wsbintegration'),
                 "GI" => __("Gibraltar", 'wsbintegration'),
                 "GR" => __("Greece", 'wsbintegration'),
                 "GL" => __("Greenland", 'wsbintegration'),
                 "GD" => __("Grenada", 'wsbintegration'),
                 "GP" => __("Guadeloupe", 'wsbintegration'),
                 "GU" => __("Guam", 'wsbintegration'),
                 "GT" => __("Guatemala", 'wsbintegration'),
                 "GG" => __("Guernsey", 'wsbintegration'),
                 "GN" => __("Guinea", 'wsbintegration'),
                 "GW" => __("Guinea-Bissau", 'wsbintegration'),
                 "GY" => __("Guyana", 'wsbintegration'),
                 "HT" => __("Haiti", 'wsbintegration'),
                 "HM" => __("Heard Island and McDonald Islands", 'wsbintegration'),
                 "HN" => __("Honduras", 'wsbintegration'),
                 "HK" => __("Hong Kong", 'wsbintegration'),
                 "HU" => __("Hungary", 'wsbintegration'),
                 "IS" => __("Iceland", 'wsbintegration'),
                 "IN" => __("India", 'wsbintegration'),
                 "ID" => __("Indonesia", 'wsbintegration'),
                 "IR" => __("Iran", 'wsbintegration'),
                 "IQ" => __("Iraq", 'wsbintegration'),
                 "IE" => __("Ireland", 'wsbintegration'),
                 "IM" => __("Isle of Man", 'wsbintegration'),
                 "IL" => __("Israel", 'wsbintegration'),
                 "IT" => __("Italy", 'wsbintegration'),
                 "JM" => __("Jamaica", 'wsbintegration'),
                 "JP" => __("Japan", 'wsbintegration'),
                 "JE" => __("Jersey", 'wsbintegration'),
                 "JT" => __("Johnston Island", 'wsbintegration'),
                 "JO" => __("Jordan", 'wsbintegration'),
                 "KZ" => __("Kazakhstan", 'wsbintegration'),
                 "KE" => __("Kenya", 'wsbintegration'),
                 "KI" => __("Kiribati", 'wsbintegration'),
                 "KW" => __("Kuwait", 'wsbintegration'),
                 "KG" => __("Kyrgyzstan", 'wsbintegration'),
                 "LA" => __("Laos", 'wsbintegration'),
                 "LV" => __("Latvia", 'wsbintegration'),
                 "LB" => __("Lebanon", 'wsbintegration'),
                 "LS" => __("Lesotho", 'wsbintegration'),
                 "LR" => __("Liberia", 'wsbintegration'),
                 "LY" => __("Libya", 'wsbintegration'),
                 "LI" => __("Liechtenstein", 'wsbintegration'),
                 "LT" => __("Lithuania", 'wsbintegration'),
                 "LU" => __("Luxembourg", 'wsbintegration'),
                 "MK" => __("Macedonia", 'wsbintegration'),
                 "MG" => __("Madagascar", 'wsbintegration'),
                 "MW" => __("Malawi", 'wsbintegration'),
                 "MY" => __("Malaysia", 'wsbintegration'),
                 "MV" => __("Maldives", 'wsbintegration'),
                 "ML" => __("Mali", 'wsbintegration'),
                 "MT" => __("Malta", 'wsbintegration'),
                 "MH" => __("Marshall Islands", 'wsbintegration'),
                 "MQ" => __("Martinique", 'wsbintegration'),
                 "MR" => __("Mauritania", 'wsbintegration'),
                 "MU" => __("Mauritius", 'wsbintegration'),
                 "YT" => __("Mayotte", 'wsbintegration'),
                 "MX" => __("Mexico", 'wsbintegration'),
                 "FM" => __("Micronesia", 'wsbintegration'),
                 "MI" => __("Midway Islands", 'wsbintegration'),
                 "MD" => __("Moldova", 'wsbintegration'),
                 "MC" => __("Monaco", 'wsbintegration'),
                 "MN" => __("Mongolia", 'wsbintegration'),
                 "ME" => __("Montenegro", 'wsbintegration'),
                 "MS" => __("Montserrat", 'wsbintegration'),
                 "MA" => __("Morocco", 'wsbintegration'),
                 "MZ" => __("Mozambique", 'wsbintegration'),
                 "MM" => __("Myanmar Burma", 'wsbintegration'),
                 "NA" => __("Namibia", 'wsbintegration'),
                 "NR" => __("Nauru", 'wsbintegration'),
                 "NP" => __("Nepal", 'wsbintegration'),
                 "NL" => __("Netherlands", 'wsbintegration'),
                 "AN" => __("Netherlands Antilles", 'wsbintegration'),
                 "NT" => __("Neutral Zone", 'wsbintegration'),
                 "NC" => __("New Caledonia", 'wsbintegration'),
                 "NZ" => __("New Zealand", 'wsbintegration'),
                 "NI" => __("Nicaragua", 'wsbintegration'),
                 "NE" => __("Niger", 'wsbintegration'),
                 "NG" => __("Nigeria", 'wsbintegration'),
                 "NU" => __("Niue", 'wsbintegration'),
                 "NF" => __("Norfolk Island", 'wsbintegration'),
                 "KP" => __("North Korea", 'wsbintegration'),
                 "MP" => __("Northern Mariana Islands", 'wsbintegration'),
                 "NO" => __("Norway", 'wsbintegration'),
                 "OM" => __("Oman", 'wsbintegration'),
                 "PK" => __("Pakistan", 'wsbintegration'),
                 "PW" => __("Palau", 'wsbintegration'),
                 "PS" => __("Palestinian Territories", 'wsbintegration'),
                 "PA" => __("Panama", 'wsbintegration'),
                 "PG" => __("Papua New Guinea", 'wsbintegration'),
                 "PY" => __("Paraguay", 'wsbintegration'),
                 "YD" => __("People’s Democratic Republic of Yemen", 'wsbintegration'),
                 "PE" => __("Peru", 'wsbintegration'),
                 "PH" => __("Philippines", 'wsbintegration'),
                 "PL" => __("Poland", 'wsbintegration'),
                 "PT" => __("Portugal", 'wsbintegration'),
                 "PR" => __("Puerto Rico", 'wsbintegration'),
                 "QA" => __("Qatar", 'wsbintegration'),
                 "RO" => __("Romania", 'wsbintegration'),
                 "RU" => __("Russia", 'wsbintegration'),
                 "RW" => __("Rwanda", 'wsbintegration'),
                 "RE" => __("Réunion", 'wsbintegration'),
                 "BL" => __("Saint Barthélemy", 'wsbintegration'),
                 "SH" => __("Saint Helena", 'wsbintegration'),
                 "KN" => __("Saint Kitts and Nevis", 'wsbintegration'),
                 "LC" => __("Saint Lucia", 'wsbintegration'),
                 "MF" => __("Saint Martin", 'wsbintegration'),
                 "PM" => __("Saint Pierre and Miquelon", 'wsbintegration'),
                 "VC" => __("Saint Vincent and the Grenadines", 'wsbintegration'),
                 "WS" => __("Samoa", 'wsbintegration'),
                 "SM" => __("San Marino", 'wsbintegration'),
                 "SA" => __("Saudi Arabia", 'wsbintegration'),
                 "SN" => __("Senegal", 'wsbintegration'),
                 "RS" => __("Serbia", 'wsbintegration'),
                 "CS" => __("Serbia and Montenegro", 'wsbintegration'),
                 "SC" => __("Seychelles", 'wsbintegration'),
                 "SL" => __("Sierra Leone", 'wsbintegration'),
                 "SG" => __("Singapore", 'wsbintegration'),
                 "SK" => __("Slovakia", 'wsbintegration'),
                 "SI" => __("Slovenia", 'wsbintegration'),
                 "SB" => __("Solomon Islands", 'wsbintegration'),
                 "SO" => __("Somalia", 'wsbintegration'),
                 "ZA" => __("South Africa", 'wsbintegration'),
                 "GS" => __("South Georgia and the South Sandwich Islands", 'wsbintegration'),
                 "KR" => __("South Korea", 'wsbintegration'),
                 "ES" => __("Spain", 'wsbintegration'),
                 "LK" => __("Sri Lanka", 'wsbintegration'),
                 "SD" => __("Sudan", 'wsbintegration'),
                 "SR" => __("Suriname", 'wsbintegration'),
                 "SJ" => __("Svalbard and Jan Mayen", 'wsbintegration'),
                 "SZ" => __("Swaziland", 'wsbintegration'),
                 "SE" => __("Sweden", 'wsbintegration'),
                 "CH" => __("Switzerland", 'wsbintegration'),
                 "SY" => __("Syria", 'wsbintegration'),
                 "ST" => __("São Tomé and Príncipe", 'wsbintegration'),
                 "TW" => __("Taiwan", 'wsbintegration'),
                 "TJ" => __("Tajikistan", 'wsbintegration'),
                 "TZ" => __("Tanzania", 'wsbintegration'),
                 "TH" => __("Thailand", 'wsbintegration'),
                 "TL" => __("Timor-Leste", 'wsbintegration'),
                 "TG" => __("Togo", 'wsbintegration'),
                 "TK" => __("Tokelau", 'wsbintegration'),
                 "TO" => __("Tonga", 'wsbintegration'),
                 "TT" => __("Trinidad and Tobago", 'wsbintegration'),
                 "TA" => __("Tristan da Cunha", 'wsbintegration'),
                 "TN" => __("Tunisia", 'wsbintegration'),
                 "TR" => __("Turkey", 'wsbintegration'),
                 "TM" => __("Turkmenistan", 'wsbintegration'),
                 "TC" => __("Turks and Caicos Islands", 'wsbintegration'),
                 "TV" => __("Tuvalu", 'wsbintegration'),
                 "UG" => __("Uganda", 'wsbintegration'),
                 "UA" => __("Ukraine", 'wsbintegration'),
                 "AE" => __("United Arab Emirates", 'wsbintegration'),
                 "GB" => __("United Kingdom", 'wsbintegration'),
                 "US" => __("United States", 'wsbintegration'),
                 "UY" => __("Uruguay", 'wsbintegration'),
                 "UZ" => __("Uzbekistan", 'wsbintegration'),
                 "VU" => __("Vanuatu", 'wsbintegration'),
                 "VA" => __("Vatican City", 'wsbintegration'),
                 "VE" => __("Venezuela", 'wsbintegration'),
                 "VN" => __("Vietnam", 'wsbintegration'),
                 "WK" => __("Wake Island", 'wsbintegration'),
                 "WF" => __("Wallis and Futuna", 'wsbintegration'),
                 "EH" => __("Western Sahara", 'wsbintegration'),
                 "YE" => __("Yemen", 'wsbintegration'),
                 "ZM" => __("Zambia", 'wsbintegration'),
                 "ZW" => __("Zimbabwe", 'wsbintegration')
    );
}

/**
 * Returns correctly-formatted date interval
 * @param $start_date DateTime Start date of the interval
 * @param $end_date DateTime End date of the interval
 * @param $without_year boolean If true, a year is not included
 * @return string
 */
function wsb_get_date_interval($start_date, $end_date, $without_year) {
    $with_year = $without_year == null || !$without_year;
    $months = [
        [__("Jan", 'wsbintegration'), __("January", 'wsbintegration')],
        [__("Feb", 'wsbintegration'), __("February", 'wsbintegration')],
        [__("Mar", 'wsbintegration'), __("March", 'wsbintegration')],
        [__("Apr", 'wsbintegration'), __("April", 'wsbintegration')],
        [__("May", 'wsbintegration'), __("May", 'wsbintegration')],
        [__("Jun", 'wsbintegration'), __("June", 'wsbintegration')],
        [__("Jul", 'wsbintegration'), __("July", 'wsbintegration')],
        [__("Aug", 'wsbintegration'), __("August", 'wsbintegration')],
        [__("Sep", 'wsbintegration'), __("September", 'wsbintegration')],
        [__("Oct", 'wsbintegration'), __("October", 'wsbintegration')],
        [__("Nov", 'wsbintegration'), __("November", 'wsbintegration')],
        [__("Dec", 'wsbintegration'), __("December", 'wsbintegration')]
    ];
    if (!($start_date && $end_date)) {
        return '';
    }
    $str_start = '';
    $str_end = '';
    if ($with_year) {
        if ($start_date->format('Y') != $end_date->format('Y')) {
            $str_start = $start_date->format('Y');
        }
        $str_end = $end_date->format('Y');
    }
    $monthIndex = 0 + (int) !$with_year;
    if ($start_date->format('m') != $end_date->format('m')) {
        $str_start = $months[(int)$start_date->format('m')][$monthIndex] . ' ' . $str_start;
    }
    $str_end = $months[(int)$end_date->format('m')][$monthIndex] . ' ' . $str_end;
    if ($start_date != $end_date) {
        $str_start = $start_date->format('d') . ' ' . $str_start;
    }
    $str_end = $end_date->format('d') . ' ' . $str_end;
    return trim($str_start) ? $str_start . ' — ' . $str_end : $str_end;
}

/**
 * Returns correct location for the event
 * @param $country string 2-letter country code
 * @param $city string City name
 *
 * @return bool
 */
function wsb_get_event_location($country, $city) {
    if ($country == '00') {
        return __('online', 'wsbintegration');
    } else {
        $country_name = wsb_get_country_name($country);
        if (strlen($country_name) > 0) {
            return $city . ', ' . $country_name;
        } else {
            return $city;
        }
    }
}
