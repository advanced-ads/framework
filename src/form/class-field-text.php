<?php
/**
 * Form text input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field text class
 */
class Field_Text extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		$input_class = HTML::classnames( 'regular-text', $this->get( 'class' ) );
		?>
		<input class="<?php echo esc_attr( $input_class ); ?>" name="<?php echo esc_attr( $this->get( 'name' ) ); ?>" type="<?php echo esc_attr( $this->get( 'type' ) ); ?>" value="<?php echo esc_attr( $this->get( 'value' ) ); ?>" />
		<?php
	}
}
