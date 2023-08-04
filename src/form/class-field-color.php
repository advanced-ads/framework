<?php
/**
 * Form color input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Field color class
 */
class Field_Color extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		?>
		<input name="<?php echo esc_attr( $this->get( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $this->get( 'value' ) ); ?>" />
		<?php
	}
}
