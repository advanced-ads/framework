<?php
/**
 * Form base input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Admin\Upgrades;
use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field class
 */
abstract class Field {

	/**
	 * Hold field data.
	 *
	 * @var array
	 */
	protected $field = null;

	/**
	 * The constructor
	 *
	 * @param array $field Field data.
	 */
	public function __construct( $field ) {
		$defaults = [
			'label'         => '',
			'placeholder'   => '',
			'class'         => '',
			'style'         => '',
			'wrapper_class' => '',
			'value'         => '',
			'name'          => $field['name'] ?? $field['id'],
			'type'          => 'text',
			'desc'          => '',
			'is_pro_pitch'  => false,
			'cols'          => 30,
			'rows'          => 10,
		];

		$this->field = wp_parse_args( $field, $defaults );
	}

	/**
	 * Get from field data by id.
	 *
	 * @param string $id          Id of the data to get.
	 * @param mixed  $default_val Default value if not set.
	 *
	 * @return mixed
	 */
	public function get( $id, $default_val = false ) {
		return $this->field[ $id ] ?? $default_val;
	}

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render_field() {
		if ( 'hidden' === $this->get( 'type' ) ) {
			$this->render();
			return;
		}

		/**
		 * This filter allows to extend the class dynamically by add-ons
		 * this would allow add-ons to dynamically hide/show only attributes belonging to them, practically not used now
		 */
		$class = apply_filters( 'advanced-ads-field-class', '', $this->get( 'id' ) );

		$classnames = HTML::classnames(
			'advads-field',
			'advads-field-' . $this->get( 'type' ),
			'advads-field-' . $this->get( 'id' ),
			$class,
			$this->get( 'wrapper_class' )
		);

		$input_wrap = 'raw' !== $this->get( 'type' ) || ! empty( $this->get( 'description' ) );

		$this->wrap_before();
		?>
		<div class="<?php echo esc_attr( $classnames ); ?>">

			<?php
			if ( $this->get( 'label' ) ) :
				$label_attrs = [];
				if ( ! in_array( $this->get( 'type' ), [ 'checkbox', 'image_selector', 'radio', 'radio_button' ], true ) ) {
					$label_attrs['for'] = $this->get( 'id' );
				}
			?>
			<div class="advads-field-label">
				<label <?php echo HTML::build_attributes( $label_attrs ); ?>>
					<?php echo esc_html( $this->get( 'label' ) ); ?>
				</label>
			</div>
			<?php endif; ?>

			<?php
			if ( $input_wrap ) {
				echo '<div class="advads-field-input">';
				$this->input();
				echo '</div>';
			} else {
				$this->input();
			}
			?>
		</div>
		<?php
		$this->wrap_after();
	}

	/**
	 * HTML before wrap
	 *
	 * @return void
	 */
	public function wrap_before() {}

	/**
	 * HTML after wrap
	 *
	 * @return void
	 */
	public function wrap_after() {}

	/**
	 * Render
	 *
	 * @return void
	 */
	abstract public function render();

	/**
	 * Render input.
	 *
	 * @return void
	 */
	private function input(): void {
		$this->render_callback( 'before' );

		$this->render_callback( 'before_field' );
		$this->render();
		$this->render_callback( 'after_field' );

		if ( $this->get( 'description' ) ) {
			echo '<div class="advads-field-description">' . wp_kses_post( $this->get( 'description' ) ) . '</div>';
		}

		if ( $this->get( 'error' ) ) {
			echo '<div class="advads-field-error">' . wp_kses_post( $this->get( 'error' ) ) . '</div>';
		}

		$this->render_callback( 'after' );
	}

	/**
	 * Render callback
	 *
	 * @param string $name Id of callback.
	 *
	 * @return void
	 */
	private function render_callback( $name ): void {
		$callback = $this->get( $name );
		if ( ! $callback ) {
			return;
		}

		if ( is_string( $callback ) ) {
			echo $callback;
		}

		if ( is_callable( $callback ) ) {
			call_user_func( $callback, $this );
		}
	}
}
