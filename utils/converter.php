<?php
/**
 * Converts JSON files in i18next format to a format, suitable for further conversion to po/mo files
 *
 * @link              https://workshopbutler.com
 * @since             2.0.0
 * @package           WSB_Integration
 */

run();

/**
 * The main function, which executes the conversion of locales
 */
function run() {
	$src_dir_name = $_SERVER['argv'][1];
	$dst_dir_name = $_SERVER['argv'][2];
	convert_files( $src_dir_name, $dst_dir_name );
}

/**
 * Converts one translation file
 *
 * @param string $file               Name of the file.
 * @param string $converted_dir_name Name of the directory to store the converted files.
 */
function convert_file( $file, $converted_dir_name ) {
	print 'Converting file ' . $file . "\n";
	$translations = json_decode( file_get_contents( $file ), true );
	if ( is_array( $translations ) ) {
		$converted_translation = convert_translation( $translations );
		$filename              = $converted_dir_name . '/' . basename( $file );
		file_put_contents( $filename, json_encode( $converted_translation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
		print 'File ' . $file . " was converted\n";
	} else {
		print 'ERROR: Cannot parse the content of file ' . $file . "\n";
	}
}

/**
 * Converts the keys of existing translation to a format, suitable for WordPress
 *
 * @param array $translation Associative array, containing the translated strings and tokens.
 *
 * @return array
 */
function convert_translation( $translation ) {
	foreach ( $translation as $key => $value ) {
		$new_key = replace_key( $key );
		if ( $new_key !== $key ) {
			unset( $translation[ $key ] );
		}
		if ( is_array( $value ) ) {
			$translation[ $new_key ] = convert_translation( $value );
		} else {
			$translation[ $new_key ] = replace_tokens( $value );
		}
	}

	return $translation;
}

/**
 * Replaces the keys 'street_1' and 'street_2' because the i18next-conv thinks these are plural forms
 *
 * @param string $key Translation key.
 *
 * @return string
 */
function replace_key( $key ) {
	if ( 'street_1' !== $key && 'street_2' !== $key ) {
		return $key;
	} else {
		return 'street_1' === $key ? 'street_first' : 'street_second';
	}
}

/**
 * Replaces tokens like {{count}}, {{date}} or references like $t(language.{{count}}) with %s, making the string
 * a correctly-formed string pattern
 *
 * @param string $value Initial translation.
 *
 * @return string
 */
function replace_tokens( $value ) {
	$value = replace_matches( '/^.*?({{[a-z\d]+}})[^{}]*?({{[a-z\d]+}})?[^{}]*$/i', $value );
	$value = replace_matches( '/^.*?(\$t\([a-z.\d%]+\))[^\$]*?(\$t\([a-z.\d%]+\))?[^\$]*$/i', $value );

	return $value;
}

/**
 * Replaces the matched tokens with %s
 *
 * @param string $pattern Matching pattern.
 * @param string $value   Translation.
 *
 * @return string
 */
function replace_matches( $pattern, $value ) {
	$matches = array();
	preg_match( $pattern, $value, $matches );
	if ( is_array( $matches ) && count( $matches ) > 1 ) {
		$value = str_replace( $matches[1], '%s', $value );
		if ( count( $matches ) > 2 ) {
			$value = str_replace( $matches[2], '%s', $value );
		}
	}

	return $value;
}

/**
 * Converts all translations from $src_dir_name directory ands saves them to $converted_dir_name directory
 *
 * @param string $src_dir_name       Name of the directory with original translations.
 * @param string $converted_dir_name Name of the directory with converted translations.
 */
function convert_files( $src_dir_name, $converted_dir_name ) {
	$dir = dir( $src_dir_name );
	if ( $dir ) {
		$entry = $dir->read();
		while ( false !== $entry ) {
			$path = $src_dir_name . '/' . $entry;
			if ( is_file( $path ) ) {
				convert_file( $path, $converted_dir_name );
			}
			$entry = $dir->read();
		}
		print "All files were converted\n";
	} else {
		print 'ERROR: Cannot find directory ' . $src_dir_name . "\n";
	}
}
