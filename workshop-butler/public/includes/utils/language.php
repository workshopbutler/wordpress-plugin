<?php
/**
 * Returns 2-letter language code from the name of the language. If the language code is unknown,
 *  the language itself is returned
 *
 * We have to do it as API returns full names of the languages right now
 *
 * @param string $lang Language
 * @return string
 */
function get_lang_code( $lang ) {
	$langs = array(
		'Arabic'     => 'AR',
		'Bulgarian'  => 'BG',
		'Chinese'    => 'ZH',
		'Croatian'   => 'HR',
		'Czech'      => 'CS',
		'Danish'     => 'DA',
		'Dutch'      => 'NL',
		'English'    => 'EN',
		'Finnish'    => 'FI',
		'French'     => 'FR',
		'German'     => 'DE',
		'Italian'    => 'IT',
		'Slovenian'  => 'SL',
		'Japanese'   => 'JA',
		'Norwegian'  => 'NO',
		'Polish'     => 'PL',
		'Portuguese' => 'PT',
		'Romanian'   => 'RO',
		'Russian'    => 'RU',
		'Spanish'    => 'ES',
		'Swedish'    => 'SV',
		'Serbian'    => 'SR',
		'Turkish'    => 'TR',
		'Vietnamese' => 'VI',
	);
	$code  = $langs[ $lang ];
	return $code ? $code : $lang;
}
