<?php
/**
 * Form radio button input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field radio button class
 */
class Field_Radio_Button extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		// Early bail!!
		if ( ! $this->get( 'options' ) ) {
			return;
		}

		$counter = 1;

		$wrap_class = HTML::classnames( 'advads-radio-buttons', $this->get( 'class' ) );
		echo '<div class=" ' . esc_attr( $wrap_class ) . '">';
		foreach ( $this->get( 'options' ) as $data ) :
			$option_id   = $this->get( 'id' ) . '-' . ( $counter++ );
			?>
			<input type="radio" id="<?php echo esc_attr( $option_id ); ?>" name="<?php echo esc_attr( $this->get( 'name' ) ); ?>" value="<?php echo esc_attr( $data['value'] ); ?>"<?php checked( $this->get( 'value' ), $data['value'] ); ?> />
			<label for="<?php echo esc_attr( $option_id ); ?>">
				<?php echo esc_html( $data['label'] ); ?>
			</label>
			<?php
		endforeach;

		echo '</div>';
	}
}
