<?php
/**
 * Array utilities
 *
 * @package AdvancedAds\Framework\Utilities
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Utilities;

use ArrayAccess;

defined( 'ABSPATH' ) || exit;

/**
 * Arr class.
 */
class Arr {

	/**
	 * Determine whether the given value is array accessible.
	 *
	 * @param mixed $value Value to check.
	 *
	 * @return bool
	 */
	public static function accessible( $value ) {
		return is_array( $value ) || $value instanceof ArrayAccess;
	}

	/**
	 * Determine if the given key exists in the provided array.
	 *
	 * @param ArrayAccess|array $array Array to check key in.
	 * @param string|int        $key   Key to check for.
	 *
	 * @return bool
	 */
	public static function exists( $array, $key ) {
		if ( $array instanceof ArrayAccess ) {
			return $array->offsetExists( $key );
		}

		return array_key_exists( $key, $array );
	}

	/**
	 * Insert a single array item inside another array at a set position
	 *
	 * @param array $array    Array to modify. Is passed by reference, and no return is needed.
	 * @param array $new      New array to insert.
	 * @param int   $position Position in the main array to insert the new array.
	 */
	public static function insert( &$array, $new, $position ) {
		$before = array_slice( $array, 0, $position - 1 );
		$after  = array_diff_key( $array, $before );
		$array  = array_merge( $before, $new, $after );
	}
}
