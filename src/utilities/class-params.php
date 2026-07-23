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
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function get( $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
		return self::input( INPUT_GET, $id, $default, $filter, $flag );
	}

	/**
	 * Get field from FORM post.
	 *
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function post( $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
		return self::input( INPUT_POST, $id, $default, $filter, $flag );
	}

	/**
	 * Get field from request.
	 *
	 * Honors PHP `request_order` (default GP) so later sources override earlier ones.
	 *
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function request( $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
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
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function cookie( $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
		return self::input( INPUT_COOKIE, $id, $default, $filter, $flag );
	}

	/**
	 * Get field from FORM server.
	 *
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	public static function server( $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
		return isset( $_SERVER[ $id ] ) ? filter_var( wp_unslash( $_SERVER[ $id ] ), $filter, $flag ) : $default;
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
	 * Falls back to the matching PHPUnit-friendly superglobal when filter_input
	 * cannot see the value (CLI / tests that assign $_GET/$_POST directly).
	 *
	 * @param int    $input   Input to get from.
	 * @param string $id      Field id to get.
	 * @param mixed  $default Default value to return if field is not found.
	 * @param int    $filter  The ID of the filter to apply.
	 * @param int    $flag    The ID of the flag to apply.
	 *
	 * @return mixed
	 */
	private static function input( $input, $id, $default = false, $filter = FILTER_DEFAULT, $flag = [] ) {
		if ( filter_has_var( $input, $id ) ) {
			return filter_input( $input, $id, $filter, $flag );
		}

		$value = self::superglobal_value( $input, $id );
		if ( null === $value ) {
			return $default;
		}

		return filter_var( wp_unslash( $value ), $filter, $flag );
	}

	/**
	 * Read a raw value from a request/cookie bag when filter_input is unavailable.
	 *
	 * @param int    $input Input constant.
	 * @param string $id    Field id.
	 *
	 * @return mixed|null
	 */
	private static function superglobal_value( $input, $id ) {
		switch ( $input ) {
			case INPUT_GET:
				return array_key_exists( $id, $_GET ) ? $_GET[ $id ] : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			case INPUT_POST:
				return array_key_exists( $id, $_POST ) ? $_POST[ $id ] : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			case INPUT_COOKIE:
				return array_key_exists( $id, $_COOKIE ) ? $_COOKIE[ $id ] : null;
			default:
				return null;
		}
	}
}
