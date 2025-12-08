<?php
/**
 * Form switch input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Field switch class
 */
class Field_Switch extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		$off_value = $this->get( 'value_off', '0' );
		$on_value = $this->get( 'value_on', '1' );
		?>
		<label for="<?php echo esc_attr( $this->get( 'id' ) ); ?>">
			<input type="hidden" name="<?php echo esc_attr( $this->get( 'name' ) ); ?>" value="<?php echo esc_attr( $off_value ); ?>" />
			<input type="checkbox" name="<?php echo esc_attr( $this->get( 'name' ) ); ?>" value="<?php echo esc_attr( $on_value ); ?>"<?php checked( $this->get( 'value' ), $on_value ); ?> />
			<?php echo esc_attr( $this->get( 'switch_label' ) ); ?>
		</label>
		<?php
	}
}
