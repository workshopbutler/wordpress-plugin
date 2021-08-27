<?php
/**
 * WorkshopButler Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package WorkshopButler\Functions
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get other templates passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array  $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 */
function wsb_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	$cache_key = sanitize_key(
		implode(
			'-',
			array(
				'template',
				$template_name,
				$template_path,
				$default_path,
				WSB()->get_version(),
			)
		)
	);
	$template  = (string) wp_cache_get( $cache_key, 'wsbintegration' );

	if ( ! $template ) {
		$template = wsb_locate_template( $template_name, $template_path, $default_path );

		// Don't cache the absolute path so that it can be shared between web servers with different paths.
		$cache_path = wsb_tokenize_path( $template, wsb_get_path_define_tokens() );

		wsb_set_template_cache( $cache_key, $cache_path );
	} else {
		// Make sure that the absolute path to the template is resolved.
		$template = wsb_untokenize_path( $template, wsb_get_path_define_tokens() );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'wsb_get_template', $template, $template_name, $args, $template_path, $default_path );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			wsb_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'wsbintegration' ), '<code>' . $filter_template . '</code>' ), WSB()->get_version() );

			return;
		}
		$template = $filter_template;
	}

	$action_args = array(
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		if ( isset( $args['action_args'] ) ) {
			wsb_doing_it_wrong( __FUNCTION__, __( 'action_args should not be overwritten when calling wsb_get_template.', 'wsbintegration' ), WSB()->get_version() );
			unset( $args['action_args'] );
		}
		extract( $args ); // @codingStandardsIgnoreLine
	}

	do_action( 'wsb_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'wsb_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @return string
 */
function wsb_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = WSB()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = WSB()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$cs_template = str_replace( '_', '-', $template_name );
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $cs_template,
			$cs_template,
		)
	);

	if ( empty( $template ) ) {
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
	}

	// Get default template/.
	if ( ! $template || WSB_TEMPLATE_DEBUG_MODE ) {
		if ( empty( $cs_template ) ) {
			$template = $default_path . $template_name;
		} else {
			$template = $default_path . $cs_template;
		}
	}

	// Return what we found.
	return apply_filters( 'wsb_locate_template', $template, $template_name, $template_path );
}

/**
 * Get template part (for templates like the schedule).
 *
 * WC_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @param mixed  $slug Template slug.
 * @param string $name Template name (default: '').
 *
 * @since 3.0.0
 */
function wsb_get_template_part( $slug, $name = '' ) {
	$cache_key = sanitize_key( implode( '-', array( 'template-part', $slug, $name, WSB()->get_version() ) ) );
	$template  = (string) wp_cache_get( $cache_key, 'wsbintegration' );

	if ( ! $template ) {
		if ( $name ) {
			$template = WSB_TEMPLATE_DEBUG_MODE ? '' : locate_template(
				array(
					"{$slug}-{$name}.php",
					WSB()->template_path() . "{$slug}-{$name}.php",
				)
			);

			if ( ! $template ) {
				$fallback = WSB()->plugin_path() . "/templates/{$slug}-{$name}.php";
				$template = file_exists( $fallback ) ? $fallback : '';
			}
		}

		if ( ! $template ) {
			// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/workshop-butler/slug.php.
			$template = WSB_TEMPLATE_DEBUG_MODE ? '' : locate_template(
				array(
					"{$slug}.php",
					WSB()->template_path() . "{$slug}.php",
				)
			);
		}

		// Don't cache the absolute path so that it can be shared between web servers with different paths.
		$cache_path = wsb_tokenize_path( $template, wsb_get_path_define_tokens() );

		wsb_set_template_cache( $cache_key, $cache_path );
	} else {
		// Make sure that the absolute path to the template is resolved.
		$template = wsb_untokenize_path( $template, wsb_get_path_define_tokens() );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'wsb_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Fetches an array containing all of the configurable path constants to be used in tokenization.
 *
 * @return array The key is the define and the path is the constant.
 * @since 3.0.0
 */
function wsb_get_path_define_tokens() {
	$defines = array(
		'ABSPATH',
		'WP_CONTENT_DIR',
		'WP_PLUGIN_DIR',
		'WPMU_PLUGIN_DIR',
		'PLUGINDIR',
		'WP_THEME_DIR',
	);

	$path_tokens = array();
	foreach ( $defines as $define ) {
		if ( defined( $define ) ) {
			$path_tokens[ $define ] = constant( $define );
		}
	}

	return apply_filters( 'wsb_get_path_define_tokens', $path_tokens );
}

/**
 * Add a template to the template cache.
 *
 * @param string $cache_key Object cache key.
 * @param string $template Located template.
 *
 * @since 3.0.0
 */
function wsb_set_template_cache( $cache_key, $template ) {
	wp_cache_set( $cache_key, $template, 'wsbintegration' );

	$cached_templates = wp_cache_get( 'cached_templates', 'wsbintegration' );
	if ( is_array( $cached_templates ) ) {
		$cached_templates[] = $cache_key;
	} else {
		$cached_templates = array( $cache_key );
	}

	wp_cache_set( 'cached_templates', $cached_templates, 'wsbintegration' );
}

/**
 * Given a path, this will convert any of the subpaths into their corresponding tokens.
 *
 * @param string $path The absolute path to tokenize.
 * @param array  $path_tokens An array keyed with the token, containing paths that should be replaced.
 *
 * @return string The tokenized path.
 * @since 3.0.0
 */
function wsb_tokenize_path( $path, $path_tokens ) {
	// Order most to least specific so that the token can encompass as much of the path as possible.
	uasort(
		$path_tokens,
		function ( $a, $b ) {
			$a = strlen( $a );
			$b = strlen( $b );

			if ( $a > $b ) {
				return - 1;
			}

			if ( $b > $a ) {
				return 1;
			}

			return 0;
		}
	);

	foreach ( $path_tokens as $token => $token_path ) {
		if ( 0 !== strpos( $path, $token_path ) ) {
			continue;
		}

		$path = str_replace( $token_path, '{{' . $token . '}}', $path );
	}

	return $path;
}

/**
 * Given a tokenized path, this will expand the tokens to their full path.
 *
 * @param string $path The absolute path to expand.
 * @param array  $path_tokens An array keyed with the token, containing paths that should be expanded.
 *
 * @return string The absolute path.
 * @since 3.0.0
 */
function wsb_untokenize_path( $path, $path_tokens ) {
	foreach ( $path_tokens as $token => $token_path ) {
		$path = str_replace( '{{' . $token . '}}', $token_path, $path );
	}

	return $path;
}


/**
 * Wrapper for _doing_it_wrong().
 *
 * @param string $function Function used.
 * @param string $message Message to log.
 * @param string $version Version the message was added in.
 *
 * @since  3.0.0
 */
function wsb_doing_it_wrong( $function, $message, $version ) {
	// @codingStandardsIgnoreStart
	$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

	if ( wp_doing_ajax() || WSB()->is_rest_api_request() ) {
		do_action( 'doing_it_wrong_run', $function, $message, $version );
		error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
	} else {
		_doing_it_wrong( $function, $message, $version );
	}
	// @codingStandardsIgnoreEnd
}
