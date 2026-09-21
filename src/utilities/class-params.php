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
	 * Reads the superglobal rather than filter_input(). filter_input() serves a
	 * snapshot the SAPI takes at request start, so it cannot see anything written
	 * to $_GET/$_POST afterwards: it bypasses hardening plugins that sanitize
	 * those in place, and it is always empty under CLI.
	 *
	 * wp_unslash() undoes wp_magic_quotes(), so callers get the same unslashed
	 * value filter_input() used to return.
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
		$bags = [
			INPUT_GET    => $_GET, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			INPUT_POST   => $_POST, // phpcs:ignore WordPress.Security.NonceVerification.Missing
			INPUT_COOKIE => $_COOKIE,
		];

		// An unknown $input, a missing key and an explicit null all mean "not sent".
		$value = $bags[ $input ][ $id ] ?? null;

		return null === $value ? $default : filter_var( wp_unslash( $value ), $filter, $flag );
	}
}
