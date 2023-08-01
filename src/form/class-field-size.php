<?php
/**
 * Form size input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Field size class
 */
class Field_Size extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		$name  = $this->get( 'name' );
		$value = $this->get( 'value' );
		?>
		<p>
			<label><?php esc_html_e( 'Width', 'advanced-ads-framework' ); ?>
			<input type="number" value="<?php echo esc_attr( $value['width'] ); ?>" name="<?php echo esc_attr( $name['width'] ); ?>"> px</label>&nbsp;

			<label><?php esc_html_e( 'Height', 'advanced-ads-framework' ); ?>
			<input type="number" value="<?php echo esc_attr( $value['height'] ); ?>" name="<?php echo esc_attr( $name['height'] ); ?>"> px</label>
		</p>
		<?php
	}
}
