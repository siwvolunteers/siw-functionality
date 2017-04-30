<?php
/**
 * Widgets Helper Class
 *
 * Orginally made by @sksmatt | www.mattvarone.com
 * https://github.com/sksmatt/WordPress-Widgets-Helper-Class
 *
 * Modified and cleaned up for my purposes.
 *
 * Widgets Helper Class is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Widgets Helper Class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     Alessandro Tesoro
 * @version    1.0.0
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    wp-widgets-helper
*/

namespace TDP;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Widgets Helper Class.
 *
 * @since 1.0.0
 */
class Widgets_Helper extends \WP_Widget {

	/**
	 * The name of the widget we're going to create.
	 *
	 * @var string
	 */
	protected $widget_name = '';

	/**
	 * The description of the widget we're going to create.
	 *
	 * @var string
	 */
	protected $widget_description = '';

	/**
	 * The fields of the widget we're going to create.
	 *
	 * @var array
	 */
	protected $widget_fields = array();

	/**
	 * Create a widget and set it's settings.
	 *
	 * @param  array $args settings.
	 * @return void
	 */
	public function init() {

		// Setup widget.
		$this->slug   = $this->get_widget_slug();
		$this->fields = $this->get_widget_fields();

		// Widget options.
		$this->options = array(
			'classname'   => $this->get_widget_slug(),
			'description' => $this->get_widget_description()
		);

		// Call parent class to create the widget.
		parent::__construct( $this->get_widget_slug(), $this->get_widget_name(), $this->options );

	}

	/**
	 * Retrieve the widget slug.
	 *
	 * @return string
	 */
	private function get_widget_slug() {

		return sanitize_title( $this->widget_name );

	}

	/**
	 * Retrieve the name of the widget.
	 *
	 * @return string
	 */
	private function get_widget_name() {

		return esc_html( $this->widget_name );

	}

	/**
	 * Retrieve the description of the widget.
	 *
	 * @return string
	 */
	private function get_widget_description() {

		return esc_html( $this->widget_description );

	}

	/**
	 * Get the fields of the widget.
	 *
	 * @return array
	 */
	private function get_widget_fields() {

		return ( is_array( $this->widget_fields ) ) ? $this->widget_fields : array();

	}

	/**
	 * Create the settings form.
	 *
	 * @param  array $instance
	 * @return void
	 */
	public function form( $instance ) {

		$this->instance = $instance;
		$form           = $this->render_fields();

		echo $form;

	}

	/**
	 * Update widget settings.
	 *
	 * @param  array $new_instance new settings.
	 * @param  array $old_instance old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		foreach ( $this->fields as $field ) {

			$slug = $field[ 'id' ];

			$instance[ $slug ] = strip_tags( $new_instance[ $slug ] );

		}

		return $instance;

	}

	/**
	 * Render each field of the widget.
	 *
	 * @param  string $output
	 * @return void
	 */
	public function render_fields( $output = '' ) {

		if( ! empty( $this->fields ) ) {

			foreach ( $this->fields as $field ) {

				$field = $this->parse_field( $field );
				$type = $field['type'];
				$method = 'render_'.$type.'_field';

				$disabled_label = array( 'checkbox' );

				$output .= '<p>';

				if( ! in_array( $type, $disabled_label ) ) {
					$output .= $this->render_field_label( $field );
				}
				$output .= $this->{$method}( $field );
				$output .= $this->render_field_description( $field );
				$output .= '</p>';

			}

		}

		return $output;

	}

	/**
	 * Parse the field and set additional required settings.
	 *
	 * @param  array $field original args.
	 * @return array        new args.
	 */
	public function parse_field( $field ) {

		$defaults = array(
			'id'      => '',
			'name'    => '',
			'desc'    => '',
			'std'     => '',
			'value'   => '',
			'_id'     => $this->get_field_id( $field[ 'id' ] ),
			'_name'   => $this->get_field_name( $field[ 'id' ] ),
			'class'   => 'widefat',
			'type'    => 'text',
			'options' => array()
		);

		$field = wp_parse_args( $field, $defaults );

		$slug = $field[ 'id' ];

		if ( isset( $this->instance[ $slug ] ) ) {

			$field[ 'value' ] = empty( $this->instance[ $slug ] ) ? '' : strip_tags( $this->instance[ $slug ] );

		} else {

			unset( $field[ 'value' ] );

		}

		return $field;

	}

	/**
	 * Render the label of the field.
	 *
	 * @param  array $field the field.
	 * @return string
	 */
	public function render_field_label( $field, $space = true ) {

		$output = '<label for="' . esc_attr( $this->get_field_id( $field['id'] ) ) . '"><strong>' . esc_html( $field['name'] ) . ':</strong></label>';

		if( $space === true ) {
			$output .= '<br/>';
		}

		return $output;

	}

	/**
	 * Render the description of the field.
	 *
	 * @param  array $field the field.
	 * @return string
	 */
	public function render_field_description( $field ) {

		return ( ! empty( $field['desc'] ) ) ? '<br/><span class="description">'. esc_html( $field['desc'] ) .'</span>' : false;

	}

	/**
	 * Render text field.
	 *
	 * @param  args $field field settings.
	 * @return string
	 */
	public function render_text_field( $field ) {

		$output = '';

		$output .= '<input type="text" ';

		if ( isset( $field[ 'class' ] ) ) {
			$output .= 'class="' . esc_attr( $field[ 'class' ] ) . '" ';
		}

		$value = isset( $field[ 'value' ] ) ? $field[ 'value' ] : $field[ 'std' ];
		$output .= 'id="' . esc_attr( $field[ '_id' ] ) . '" name="' . esc_attr( $field[ '_name' ] ) . '" value="' . esc_attr( $value ) . '" ';

		if ( isset( $field[ 'size' ] ) ) {
			$output .= 'size="' . esc_attr( $field[ 'size' ] ) . '" ';
		}

		$output .= ' />';

		return $output;


	}

	/**
	 * Render textarea field.
	 *
	 * @param  args $field field settings.
	 * @return string
	 */
	public function render_textarea_field( $field ) {

		$output = '<textarea ';

		if ( isset( $field[ 'class' ] ) ) {
			$output .= 'class="' . esc_attr( $field[ 'class' ] ) . '" ';
		}

		if ( isset( $field[ 'rows' ] ) ) {
			$output .= 'rows="' . esc_attr( $field[ 'rows' ] ) . '" ';
		}

		if ( isset( $field[ 'cols' ] ) ) {
			$output .= 'cols="' . esc_attr( $field[ 'cols' ] ) . '" ';
		}

		$value = isset( $field[ 'value' ] ) ? $field[ 'value' ] : $field[ 'std' ];

		$output .= 'id="' . esc_attr( $field[ '_id' ] ) . '" name="' . esc_attr( $field[ '_name' ] ) . '">' . esc_html( $value );

		$output .= '</textarea>';

		return $output;

	}

	/**
	 * Render checkbox field.
	 *
	 * @param  args $field field settings.
	 * @return string
	 */
	public function render_checkbox_field( $field ) {

		$output = '<input type="checkbox" ';

		if ( isset( $field[ 'class' ] ) ) {
			$output .= 'class="' . esc_attr( $field[ 'class' ] ) . '" ';
		}

		$output .= 'id="' . esc_attr( $field[ '_id' ] ) . '" name="' . esc_attr( $field[ '_name' ] ) . '" value="1" ';

		if ( ( isset( $field[ 'value' ] ) && $field[ 'value' ] == 1 ) OR ( ! isset( $field[ 'value' ] ) && $field[ 'std' ] == 1 ) ) {
			$output .= ' checked="checked" ';
		}

		$output .= ' /> '.$this->render_field_label( $field, false );

		return $output;

	}

	/**
	 * Render select field.
	 *
	 * @param  args $field field settings.
	 * @return string
	 */
	public function render_select_field( $field ) {

		$output = '<select id="' . esc_attr( $field[ '_id' ] ) . '" name="' . esc_attr( $field[ '_name' ] ) . '" ';

		if ( isset( $field[ 'class' ] ) ) {
			$output .= 'class="' . esc_attr( $field[ 'class' ] ) . '" ';
		}

		$output .= '> ';

		$selected = isset( $field[ 'value' ] ) ? $field[ 'value' ] : $field[ 'std' ];

		foreach ( $field[ 'options' ] as $value => $label ) {

			$output .= '<option value="' . esc_attr( $value ) . '" ';

			if ( esc_attr( $selected ) == $value ) {
				$output .= ' selected="selected" ';
			}

			$output .= '> ' . esc_html( $label ) . '</option>';

		}

		$output .= ' </select> ';

		return $output;

	}

}
