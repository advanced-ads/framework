<?php
/**
 * Params class
 *
 * Easy access to inputs from
 *    INPUT_COOKIE
 *    INPUT_GET
 *    INPUT_POST
 *    INPUT_REQUEST
 *    INPUT_ENV
 *    INPUT_SERVER
 *
 * @package AdvancedAds\Framework\Utilities
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Utilities;

/**
 * Params class
 */
class Params {

	/**
	 * Get field from query string.
	 *
	 * Without an explicit $filter, values are sanitized (sanitize_text_field for
	 * strings; recursive for arrays). Pass FILTER_DEFAULT / FILTER_UNSAFE_RAW
	 * (or another filter) when the caller needs the raw value — e.g. HTML.
	 *
	 * @param string   $id      Field id to get.
	 * @param mixed    $default Default value to return if field is not found.
	 * @param int|null $filter  The ID of the filter to apply; null = sanitize.
	 * @param int|array $flag   The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function get( $id, $default = false, $filter = null, $flag = [] ) {
		return self::input( INPUT_GET, $id, $default, $filter, $flag );
	}

	/**
	 * Get field from FORM post.
	 *
	 * Without an explicit $filter, values are sanitized (sanitize_text_field for
	 * strings; recursive for arrays). Pass FILTER_DEFAULT / FILTER_UNSAFE_RAW
	 * (or another filter) when the caller needs the raw value — e.g. HTML.
	 *
	 * @param string   $id      Field id to get.
	 * @param mixed    $default Default value to return if field is not found.
	 * @param int|null $filter  The ID of the filter to apply; null = sanitize.
	 * @param int|array $flag   The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function post( $id, $default = false, $filter = null, $flag = [] ) {
		return self::input( INPUT_POST, $id, $default, $filter, $flag );
	}

	/**
	 * Get field from request.
	 *
	 * Honors PHP `request_order` (default GP) so later sources override earlier ones.
	 * Without an explicit $filter, values are sanitized like get()/post()/cookie().
	 *
	 * @param string   $id      Field id to get.
	 * @param mixed    $default Default value to return if field is not found.
	 * @param int|null $filter  The ID of the filter to apply; null = sanitize.
	 * @param int|array $flag   The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function request( $id, $default = false, $filter = null, $flag = [] ) {
		$request_filters = [
			'G' => INPUT_GET,
			'P' => INPUT_POST,
			'C' => INPUT_COOKIE,
		];

		$request_order = ini_get( 'request_order' ) ? ini_get( 'request_order' ) : 'GP';
		$request_order = array_reverse( str_split( $request_order ) );
		$not_found     = new \stdClass();

		foreach ( $request_order as $r ) {
			if ( ! isset( $request_filters[ $r ] ) ) {
				continue;
			}

			$value = self::input( $request_filters[ $r ], $id, $not_found, $filter, $flag );
			if ( $not_found !== $value ) {
				return $value;
			}
		}

		return $default;
	}

	/**
	 * Get field from FORM cookie.
	 *
	 * Without an explicit $filter, values are sanitized (sanitize_text_field for
	 * strings; recursive for arrays). Pass FILTER_DEFAULT / FILTER_UNSAFE_RAW
	 * (or another filter) when the caller needs the raw value.
	 *
	 * @param string   $id      Field id to get.
	 * @param mixed    $default Default value to return if field is not found.
	 * @param int|null $filter  The ID of the filter to apply; null = sanitize.
	 * @param int|array $flag   The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function cookie( $id, $default = false, $filter = null, $flag = [] ) {
		return self::input( INPUT_COOKIE, $id, $default, $filter, $flag );
	}

	/**
	 * Get field from FORM server.
	 *
	 * $_SERVER values can be set by the request, so without an explicit filter they are sanitized:
	 * URLs (REQUEST_URI, HTTP_REFERER) keep their %xx encoding, everything else is treated as plain text.
	 *
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function server( $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
		if ( ! isset( $_SERVER[ $id ] ) ) {
			return $default;
		}

		$value = function_exists( 'wp_unslash' ) ? wp_unslash( $_SERVER[ $id ] ) : $_SERVER[ $id ]; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below.

		if ( FILTER_DEFAULT !== $filter ) {
			return filter_var( $value, $filter, $flag );
		}

		if ( ! is_string( $value ) ) {
			return $default;
		}

		if ( in_array( $id, [ 'REQUEST_URI', 'HTTP_REFERER' ], true ) ) {
			return function_exists( 'esc_url_raw' ) ? esc_url_raw( $value ) : filter_var( strip_tags( $value ), FILTER_SANITIZE_URL ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- fallback outside WordPress.
		}

		return function_exists( 'sanitize_text_field' ) ? sanitize_text_field( $value ) : trim( strip_tags( $value ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- fallback outside WordPress.
	}

	/**
	 * Get field from FORM env.
	 *
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function env( $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
		return isset( $_ENV[ $id ] ) ? filter_var( wp_unslash( $_ENV[ $id ] ), $filter, $flag ) : $default;
	}

	/**
	 * Get field from input.
	 *
	 * Reads the superglobal rather than filter_input(). filter_input() serves a
	 * snapshot the SAPI takes at request start, so it cannot see anything written
	 * to $_GET/$_POST afterwards: it bypasses hardening plugins that sanitize
	 * those in place, and it is always empty under CLI.
	 *
	 * wp_unslash() undoes wp_magic_quotes(), so callers get the same unslashed
	 * value filter_input() used to return.
	 *
	 * When $filter is null, values are sanitized with sanitize_text_field()
	 * (recursively for arrays). An explicit filter (including FILTER_DEFAULT)
	 * is passed to filter_var() unchanged.
	 *
	 * @param int      $input   Input to get from.
	 * @param string   $id      Field id to get.
	 * @param mixed    $default Default value to return if field is not found.
	 * @param int|null $filter  The ID of the filter to apply; null = sanitize.
	 * @param int|array $flag   The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	private static function input( $input, $id, $default = false, $filter = null, $flag = [] ) {
		$bags = [
			INPUT_GET    => $_GET, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			INPUT_POST   => $_POST, // phpcs:ignore WordPress.Security.NonceVerification.Missing
			INPUT_COOKIE => $_COOKIE,
		];

		// An unknown $input, a missing key and an explicit null all mean "not sent".
		$value = $bags[ $input ][ $id ] ?? null;

		if ( null === $value ) {
			return $default;
		}

		$value = function_exists( 'wp_unslash' ) ? wp_unslash( $value ) : $value;

		if ( null === $filter ) {
			return self::sanitize_value( $value );
		}

		return filter_var( $value, $filter, $flag );
	}

	/**
	 * Sanitize a request value: strings via sanitize_text_field, arrays recursively.
	 *
	 * @param mixed $value Raw unslashed value.
	 *
	 * @return mixed
	 */
	private static function sanitize_value( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $item ) {
				$value[ $key ] = self::sanitize_value( $item );
			}

			return $value;
		}

		if ( ! is_string( $value ) ) {
			return $value;
		}

		return function_exists( 'sanitize_text_field' ) ? sanitize_text_field( $value ) : trim( strip_tags( $value ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- fallback outside WordPress.
	}
}
