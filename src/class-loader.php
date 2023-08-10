<?php
/**
 * Class that manages loading integrations.
 *
 * @package AdvancedAds\Framework
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Loader class
 */
class Loader {
	/**
	 * The registered integrations.
	 *
	 * @var string[]
	 */
	protected $integrations = [];

	/**
	 * The registered integrations.
	 *
	 * @var string[]
	 */
	protected $initializers = [];

	/**
	 * The registered routes.
	 *
	 * @var string[]
	 */
	protected $routes = [];

	/**
	 * Registers an integration.
	 *
	 * @param string $integration_class The class name of the integration to be loaded.
	 *
	 * @return void
	 */
	public function register_integration( $integration_class ) {
		$this->integrations[] = $integration_class;
	}

	/**
	 * Registers an initializer.
	 *
	 * @param string $initializer_class The class name of the initializer to be loaded.
	 *
	 * @return void
	 */
	public function register_initializer( $initializer_class ) {
		$this->initializers[] = $initializer_class;
	}

	/**
	 * Registers a route.
	 *
	 * @param string $route_class The class name of the route to be loaded.
	 *
	 * @return void
	 */
	public function register_route( $route_class ) {
		$this->routes[] = $route_class;
	}

	/**
	 * Loads all registered classes if their conditionals are met.
	 *
	 * @return void
	 */
	public function load() {
		$this->load_initializers();

		if ( ! \did_action( 'init' ) ) {
			\add_action( 'init', [ $this, 'load_integrations' ] );
		} else {
			$this->load_integrations();
		}

		\add_action( 'rest_api_init', [ $this, 'load_routes' ] );
	}

	/**
	 * Loads all registered initializers if their conditionals are met.
	 *
	 * @return void
	 */
	protected function load_initializers() {
		foreach ( $this->initializers as $class ) {
			$initializer = $this->get_class( $class );

			if ( null === $initializer ) {
				continue;
			}

			$initializer->initialize();
		}
	}

	/**
	 * Loads all registered integrations if their conditionals are met.
	 *
	 * @return void
	 */
	public function load_integrations() {
		foreach ( $this->integrations as $class ) {
			$integration = $this->get_class( $class );

			if ( null === $integration ) {
				continue;
			}

			$integration->hooks();
		}
	}

	/**
	 * Loads all registered routes if their conditionals are met.
	 *
	 * @return void
	 */
	public function load_routes() {
		foreach ( $this->routes as $class ) {
			$route = $this->get_class( $class );

			if ( null === $route ) {
				continue;
			}

			$route->register_routes();
		}
	}

	/**
	 * Gets a class from the container.
	 *
	 * @param string $class_name The class name.
	 *
	 * @return object|null The class or, in production environments, null if it does not exist.
	 */
	protected function get_class( $class_name ) {
		if ( \class_exists( $class_name, true ) ) {
			return new $class_name();
		}

		return null;
	}
}
