<?php
/**
 * Assets registry handles the registration of stylesheets and scripts required for plugin functionality.
 *
 * @package AdvancedAds\Framework
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework;

use AdvancedAds\Framework\Interfaces\Integration_Interface;

defined( 'ABSPATH' ) || exit;

/**
 * Assets Registry.
 */
abstract class Assets_Registry implements Integration_Interface {

	/**
	 * Version of plugin local asset.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Prefix to use in handle to make it unique.
	 *
	 * @var string
	 */
	const PREFIX = 'advads';

	/**
	 * Enqueue stylesheet
	 *
	 * @param string $handle Name of the stylesheet.
	 *
	 * @return void
	 */
	public static function enqueue_style( $handle ): void {
		wp_enqueue_style( static::prefix_it( $handle ) );
	}

	/**
	 * Enqueue script
	 *
	 * @param string $handle Name of the script.
	 *
	 * @return void
	 */
	public static function enqueue_script( $handle ): void {
		wp_enqueue_script( static::prefix_it( $handle ) );
	}

	/**
	 * Prefix the handle
	 *
	 * @param string $handle Name of the asset.
	 *
	 * @return string
	 */
	public static function prefix_it( $handle ): string {
		return static::PREFIX . '-' . $handle;
	}

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 0 );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ], 0 );
	}

	/**
	 * Register assets
	 *
	 * @return void
	 */
	public function register_assets(): void {
		$this->register_styles();
		$this->register_scripts();
	}

	/**
	 * Register styles
	 *
	 * @return void
	 */
	public function register_styles(): void {}

	/**
	 * Register scripts
	 *
	 * @return void
	 */
	public function register_scripts(): void {}

	/**
	 * Register stylesheet
	 *
	 * @param string           $handle Name of the stylesheet. Should be unique.
	 * @param string|bool      $src    URL of the stylesheet.
	 * @param string[]         $deps   Optional. An array of registered stylesheet handles this stylesheet depends on.
	 * @param string|bool|null $ver    Optional. String specifying stylesheet version number.
	 * @param string           $media  Optional. The media for which this stylesheet has been defined.
	 *
	 * @return void
	 */
	protected function register_style( $handle, $src, $deps = [], $ver = false, $media = 'all' ) {
		if ( false === $ver ) {
			$ver = static::VERSION;
		}

		wp_register_style( static::prefix_it( $handle ), ADVADS_BASE_URL . $src, $deps, $ver, $media );
	}

	/**
	 * Register script
	 *
	 * @param string           $handle    Name of the stylesheet. Should be unique.
	 * @param string|bool      $src       URL of the stylesheet.
	 * @param string[]         $deps      Optional. An array of registered stylesheet handles this stylesheet depends on.
	 * @param string|bool|null $ver       Optional. String specifying stylesheet version number.
	 * @param bool             $in_footer Optional. The media for which this stylesheet has been defined.
	 *
	 * @return void
	 */
	protected function register_script( $handle, $src, $deps = [], $ver = false, $in_footer = false ) {
		if ( false === $ver ) {
			$ver = static::VERSION;
		}

		wp_register_script( static::prefix_it( $handle ), ADVADS_BASE_URL . $src, $deps, $ver, $in_footer );
	}
}
