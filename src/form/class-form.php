<?php
/**
 * Form rendering utility functions
 *
 * @package AdvancedAds\Framework\Form
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework\Form;

use Exception;
use ftp;

defined( 'ABSPATH' ) || exit;

/**
 * Form class
 */
class Form {

	/**
	 * Hold fields.
	 *
	 * @var array
	 */
	private $fields = [];

	/**
	 * Get field type
	 *
	 * @param array $field Field array.
	 *
	 * @return Field
	 */
	public static function get_field_type( $field ) {
		$field_types = [
			'checkbox'       => Field_Checkbox::class,
			'color'          => Field_Color::class,
			'number'         => Field_Text::class,
			'position'       => Field_Position::class,
			'password'       => Field_Text::class,
			'radio'          => Field_Radio::class,
			'select'         => Field_Select::class,
			'selector'       => Field_Selector::class,
			'size'           => Field_Size::class,
			'switch'         => Field_Switch::class,
			'text'           => Field_Text::class,
			'textarea'       => Field_Textarea::class,
			'image_selector' => Field_Image_Selector::class,
			'radio_button'   => Field_Radio_Button::class,
			'group'          => Field_Group::class,
			'raw'            => Field_Raw::class,
		];

		$type = $field['type'];

		$class = isset( $field_types[ $type ] ) ? $field_types[ $type ] : $field_types['text'];

		return new $class( $field );
	}

	/**
	 * Add field.
	 *
	 * @throws Exception If no id is define.
	 * @throws Exception If no type is define.
	 *
	 * @param string $id   Unique identifier of field.
	 * @param array  $args Field args.
	 *
	 * @return void
	 */
	public function add_field( $id, $args ) {
		// Checks.
		if ( ! isset( $args['id'] ) || empty( $args['id'] ) ) {
			throw new Exception( 'A field must have an id.' );
		}

		if ( ! isset( $args['type'] ) || empty( $args['type'] ) ) {
			throw new Exception( 'A field must have a type.' );
		}

		if ( ! isset( $args['order'] ) ) {
			$args['order'] = 10;
		}

		$this->fields[ $id ] = $args;
	}

	/**
	 * Get field.
	 *
	 * @param string $id Field id to get.
	 *
	 * @return null|Field
	 */
	public function get_field( $id ) {
		if ( isset( $this->fields[ $id ] ) ) {
			return $this->fields[ $id ];
		}

		return null;
	}

	/**
	 * Update field.
	 *
	 * @param string $id   Field id to update.
	 * @param array  $args Field args.
	 *
	 * @return null|Field
	 */
	public function update_field( $id, $args ) {
		$this->remove_field( $id );
		$this->add_field( $id, $args );
	}

	/**
	 * Remove field.
	 *
	 * @param string $id Field id to remove.
	 *
	 * @return void
	 */
	public function remove_field( $id ) {
		if ( isset( $this->fields[ $id ] ) ) {
			unset( $this->fields[ $id ] );
		}
	}

	/**
	 * Render form.
	 *
	 * @return void
	 */
	public function render() {
		usort( $this->fields, fn( $a, $b ) => $a['order'] <=> $b['order'] );

		foreach ( $this->fields as $field ) {
			$object = self::get_field_type( $field );

			if ( $object ) {
				$object->render_field();
			}
		}
	}
}
