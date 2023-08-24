<?php
/**
 * JSON manger class
 *
 * It handles json output for use on backend and frontend.
 *
 * @package AdvancedAds\Framework
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework;

use InvalidArgumentException;

defined( 'ABSPATH' ) || exit;

/**
 * JSON class
 */
class JSON {

	/**
	 * JSON Holder.
	 *
	 * @var array
	 */
	private $data = [];

	/**
	 * Default Object name.
	 *
	 * @var string
	 */
	private $default_object_name = null;

	/**
	 * The constructor
	 *
	 * @param string $object_name Object name to be used.
	 */
	public function __construct( $object_name ) {
		$this->default_object_name = $object_name;
	}

	/**
	 * Bind all hooks.
	 *
	 * @since 1.0.0
	 *
	 * @throws InvalidArgumentException When object name not defined.
	 *
	 * @return JSON
	 */
	public function hooks() {
		if ( empty( $this->default_object_name ) ) {
			throw new InvalidArgumentException( 'Please set default object name to be used when printing JSON.' );
		}

		$hook = is_admin() ? 'admin_footer' : 'wp_footer';
		add_action( $hook, [ $this, 'output' ], 0 );
		return $this;
	}

	/**
	 * Add to JSON object.
	 *
	 * @since  1.0.0
	 *
	 * @param string $key         Unique identifier.
	 * @param mixed  $value       The data itself can be either a single or an array.
	 * @param string $object_name Name for the JavaScript object.
	 *                            Passed directly, so it should be qualified JS variable.
	 * @return JSON
	 */
	public function add( $key, $value, $object_name = false ) {
		// Early Bail!!
		if ( empty( $key ) ) {
			return $this;
		}

		if ( empty( $object_name ) ) {
			$object_name = $this->default_object_name;
		}

		// If key doesn't exists.
		if ( ! isset( $this->data[ $object_name ][ $key ] ) ) {
			$this->data[ $object_name ][ $key ] = $value;
			return $this;
		}

		// If key already exists.
		$old_value = $this->data[ $object_name ][ $key ];

		// If both array merge them.
		if ( is_array( $old_value ) && is_array( $value ) ) {
			$this->data[ $object_name ][ $key ] = array_merge( $old_value, $value );
			return $this;
		}

		$this->data[ $object_name ][ $key ] = $value;
		return $this;
	}

	/**
	 * Remove from JSON object.
	 *
	 * @since  1.0.0
	 *
	 * @param string $key         Unique identifier.
	 * @param string $object_name Name for the JavaScript object.
	 *                            Passed directly, so it should be qualified JS variable.
	 * @return JSON
	 */
	public function remove( $key, $object_name = false ) {
		// Early Bail!!
		if ( empty( $key ) ) {
			return;
		}

		if ( empty( $object_name ) ) {
			$object_name = $this->default_object_name;
		}

		if ( isset( $this->data[ $object_name ][ $key ] ) ) {
			unset( $this->data[ $object_name ][ $key ] );
		}

		return $this;
	}

	/**
	 * Clear all data.
	 *
	 * @since 1.0.0
	 *
	 * @return JOSN
	 */
	public function clear_all() {
		$this->data                               = [];
		$this->data[ $this->default_object_name ] = [];

		return $this;
	}

	/**
	 * Print data.
	 *
	 * @since  1.0.0
	 */
	public function output() {
		$script = $this->encode();
		if ( ! $script ) {
			return;
		}

		echo "<script type='text/javascript'>\n"; // CDATA and type='text/javascript' is not needed for HTML 5.
		echo "/* <![CDATA[ */\n";
		echo "$script\n"; // phpcs:ignore
		echo "/* ]]> */\n";
		echo "</script>\n";
	}

	/**
	 * Get encoded string.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	private function encode() {
		$script = '';
		foreach ( $this->data as $object_name => $object_data ) {
			$script .= $this->single_object( $object_name, $object_data );
		}

		return $script;
	}

	/**
	 * Encode single object.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $object_name Object name to use as JS variable.
	 * @param  array  $object_data Object data to json encode.
	 * @return array
	 */
	private function single_object( $object_name, $object_data ) {
		if ( empty( $object_data ) ) {
			return '';
		}

		foreach ( (array) $object_data as $key => $value ) {
			if ( ! is_scalar( $value ) ) {
				continue;
			}

			$object_data[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}

		return "var $object_name = " . wp_json_encode( $object_data ) . ';' . PHP_EOL;
	}
}
