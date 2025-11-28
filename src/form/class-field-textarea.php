<?php
/**
 * Form textarea input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field textarea class
 */
class Field_Textarea extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		$input_class = HTML::classnames( 'regular-text', $this->get( 'class' ) );
		?>
		<textarea class="<?php echo esc_attr( $input_class ); ?>" name="<?php echo esc_attr( $this->get( 'name' ) ); ?>" id="<?php echo esc_attr( $this->get( 'id' ) ); ?>" cols="<?php echo esc_attr( $this->get( 'cols' ) ); ?>" rows="<?php echo esc_attr( $this->get( 'rows' ) ); ?>">
			<?php echo esc_textarea( $this->get( 'value' ) ); ?>
		</textarea>
		<?php
	}
}
