<?php
/**
 * Form raw input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field raw class
 */
class Field_Raw extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		$callback = $this->get( 'callback' );
		if ( is_callable( $callback ) ) {
			$callback();
		}
	}
}
