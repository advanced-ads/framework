<?php
/**
 * Form radio input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field radio class
 */
class Field_Radio extends Field {

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

		$wrap_class = HTML::classnames( 'advads-radio-list', $this->get( 'class' ) );
		echo '<div class=" ' . esc_attr( $wrap_class ) . '">';

		foreach ( $this->get( 'options' ) as $data ) :
			$option_id   = $this->get( 'id' ) . '-' . ( $counter++ );
			$title       = $data['title'] ?? '';
			$description = $data['description'] ?? '';
			$image       = $data['image'] ?? '';
			$item_class  = HTML::classnames(
				'advads-radio-item',
				$data['item_class'] ?? '',
				$image ? 'has-image' : 'no-image'
			);

			$input_attrs = [
				'type'  => 'radio',
				'id'    => $option_id,
				'name'  => $this->get( 'name' ),
				'value' => $data['value' ],
			];

			if ( (string) $this->get( 'value' ) === (string) $data['value'] ) {
				$input_attrs['checked'] = 'checked';
			}

			?>
			<input <?php echo HTML::build_attributes( $input_attrs ); ?> />
			<label class="<?php echo esc_attr( $item_class ); ?>" for="<?php echo esc_attr( $option_id ); ?>">
				<span class="advads-radio-item-dot" aria-hidden="true"></span>

				<!-- thumbnail image -->
				<?php if ( ! empty( $image ) ) : ?>
					<img class="advads-radio-item-thumb" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
					<!-- tooltip meta (visible on hover/focus) -->
					<div class="advads-radio-item-tooltip" role="tooltip">
						<div class="advads-radio-item-title"><?php echo esc_html( $title ); ?></div>
						<div class="advads-radio-item-description"><?php echo esc_html( $description ); ?></div>
					</div>
				<?php else : ?>
					<!-- inline meta (hidden when image exists) -->
					<div class="advads-radio-item-meta">
						<div class="advads-radio-item-title"><?php echo esc_html( $title ); ?></div>
						<div class="advads-radio-item-description"><?php echo esc_html( $description ); ?></div>
					</div>
				<?php endif; ?>
			</label>
			<?php
		endforeach;

		echo '</div>';
	}
}
