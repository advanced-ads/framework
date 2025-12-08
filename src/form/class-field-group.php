<?php
/**
 * Form group input
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use AdvancedAds\Framework\Utilities\HTML;

defined( 'ABSPATH' ) || exit;

/**
 * Field group class
 */
class Field_Group extends Field {

	/**
	 * Render field
	 *
	 * @return void
	 */
	public function render() {
		// Early bail!!
		if ( ! $this->get( 'fields' ) ) {
			return;
		}

		foreach ( $this->get( 'fields' ) as $field ) {
			$object = Form::get_field_type( $field );

			if ( $object ) {
				$object->render_field();
			}
		}
	}
}
