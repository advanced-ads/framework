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

	const AS_INTEGRATIONS = 'integrations';
	const AS_INITIALIZERS = 'initializers';

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
	 * Hold containers
	 *
	 * @var array
	 */
	protected $containers = [];

	/**
	 * Get container.
	 *
	 * @param string $container_id Container id or alias.
	 *
	 * @return mixed
	 */
	public function get_container( $container_id ) {
		return isset( $this->containers[ $container_id ] ) ?? null;
	}

	/**
	 * Add container.
	 *
	 * @param string $container_id Container id or alias.
	 * @param mixed  $container    Container instance.
	 * @param string $as           Add container as.
	 *
	 * @return mixed
	 */
	public function add_container( $container_id, $container, $as = '' ) {
		$this->containers[ $container_id ] = $container;

		// Add pre initialized container to load routines.
		$this->{$as}[] = $container;
	}

	/**
	 * Registers an integration.
	 *
	 * @param string $integration The class name of the integration to be loaded.
	 * @param string $alias       The class alias.
	 *
	 * @return void
	 */
	public function register_integration( $integration, $alias = '' ) {
		if ( '' === $alias ) {
			$this->integrations[] = $integration;
		} else {
			$this->integrations[ $alias ] = $integration;
		}
	}

	/**
	 * Registers an initializer.
	 *
	 * @param string $initializer The class name of the initializer to be loaded.
	 * @param string $alias       The class alias.
	 * @return void
	 */
	public function register_initializer( $initializer, $alias = '' ) {
		if ( '' === $alias ) {
			$this->initializers[] = $initializer;
		} else {
			$this->initializers[ $alias ] = $initializer;
		}
	}

	/**
	 * Registers a route.
	 *
	 * @param string $router The class name of the route to be loaded.
	 * @param string $alias  The class alias.
	 *
	 * @return void
	 */
	public function register_route( $router, $alias = '' ) {
		if ( '' === $alias ) {
			$this->routes[] = $router;
		} else {
			$this->routes[ $alias ] = $router;
		}
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
		foreach ( $this->initializers as $alias => $class ) {
			$this->create_container( $class, 'initialize', $alias );
		}
	}

	/**
	 * Loads all registered integrations if their conditionals are met.
	 *
	 * @return void
	 */
	public function load_integrations() {
		foreach ( $this->integrations as $alias => $class ) {
			$this->create_container( $class, 'hooks', $alias );
		}
	}

	/**
	 * Loads all registered routes if their conditionals are met.
	 *
	 * @return void
	 */
	public function load_routes() {
		foreach ( $this->routes as $alias => $class ) {
			$this->create_container( $class, 'register_routes', $alias );
		}
	}

	/**
	 * Create container if needed.
	 *
	 * @param string $class  Class name.
	 * @param string $method Method to execute.
	 * @param string $alias  Class alias.
	 *
	 * @return void
	 */
	private function create_container( $class, $method, $alias ): void {
		// Class already loaded.
		if ( is_string( $class ) ) {
			return;
		}

		$container = $this->get_class( $class );
		if ( null === $container ) {
			return;
		}

		$container->$method();

		if ( is_string( $alias ) ) {
			$this->containers[ $alias ] = $container;
		}
	}

	/**
	 * Gets a class from the container.
	 *
	 * @param string $class_name The class name.
	 *
	 * @return object|null The class or, in production environments, null if it does not exist.
	 */
	private function get_class( $class_name ) {
		if ( \class_exists( $class_name, true ) ) {
			return new $class_name();
		}

		return null;
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param bool|string $value Constant value.
	 *
	 * @return void
	 */
	protected function define( $name, $value ): void {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
}
