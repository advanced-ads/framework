<?php
/**
 * Form checkbox input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field checkbox class
 */
class Field_Checkbox extends Field {

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

		$count = count( $this->get( 'options' ) );
		$name = $this->get( 'name' ) . ( 1 === $count ? '' : '[]' );

		$wrap_class = HTML::classnames( 'advads-checkbox-list', $this->get( 'class' ) );
		echo '<div class=" ' . esc_attr( $wrap_class ) . '">';
		foreach ( $this->get( 'options' ) as $item ) :
			?>
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $item['value'] ); ?>"<?php checked( $this->get( 'value' ), $item['value'] ); ?> /><?php echo esc_html( $item['label'] ); ?>
			</label>
			<?php
		endforeach;

		echo '</div>';
	}
}
