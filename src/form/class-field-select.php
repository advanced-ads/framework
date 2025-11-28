<?php
/**
 * Form select input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field select class
 */
class Field_Select extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		$value = $this->get( 'value' );
		$value = is_array( $value ) ? $value : [ $value ];

		$attrs = [
			'class' => HTML::classnames( 'regular-text', sanitize_html_class( $this->get( 'class' ) ) ),
			'id'    => $this->get( 'id' ),
			'name'  => $this->get( 'name' ),
		];

		if ( $this->get( 'multiple', false ) ) {
			$attrs['multiple'] = 'multiple';
		}
		?>
		<select <?php echo HTML::build_attributes( $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php foreach ( $this->get( 'options' ) as $option ) : ?>
				<?php if ( $option['items'] ) : ?>
				<optgroup label="<?php echo esc_attr( $option['label'] ); ?>">
					<?php
					foreach ( $option['items'] as $option ) {
						$this->render_option( $option, $value );
					}
					?>
				</optgroup>
				<?php else : ?>
					<?php $this->render_option( $option, $value ); ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Render option
	 *
	 * @param array $option Option data.
	 * @param mixed $value Current value.
	 *
	 * @return void
	 */
	private function render_option( $option, $value ) {
		$selected = in_array( $option['value'], $value, true );
		?>
		<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $selected, $option['value'] ); ?>>
			<?php echo esc_html( $option['label'] ); ?>
		</option>
		<?php
	}
}
