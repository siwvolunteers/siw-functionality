<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys
 * @version     3.1.5
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_html5' ) ) {

	/**
	 * Main ReduxFramework_custom_field class
	 *
	 * @since       1.0.0
	 */
	class ReduxFramework_html5 extends ReduxFramework {

		/**
		* Field Constructor.
		*
		* Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		*
		* @since       1.0.0
		* @access      public
		* @return      void
		 */
		function __construct( $field = array(), $value ='', $parent ) {
			$this->parent = $parent;
			$this->field = $field;
			$this->value = $value;

			// Set default args for this field to avoid bad indexes. Change this to anything you use.
			$defaults = array(
				//'key' => 'value',
			);
			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 *
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */

		public function render() {

			$field = $this->field;
			//beschikbare input types
			$html5_fields = ['date', 'tel', 'number', 'url', 'time', 'email'];

			$type = 'text';
			if (isset ( $field['html5']) && in_array( $field['html5'], $html5_fields )){
				$type = $field['html5'];
			}
			$placeholder = ( isset( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_attr( $field['placeholder'] ) . '" ' : '';

			//minimum en maximum (alleen van toepassing voor number)
			$min = ( 'number' == $type && isset( $field['min'] ) ) ? ' min="' . esc_attr( $field['min'] ) . '" ' : '';
			$max = ( 'number' == $type && isset( $field['max'] ) ) ? ' max="' . esc_attr($field['max'] ) . '" ' : '';

			//Tekst voor en na input
			$before = ( isset( $field['before'] ) ) ? esc_html( $field['before'] ) : '';
			$after = ( isset( $field['after'] ) ) ? esc_html( $field['after'] ) : '';

			echo $before . '<input type="'. $type . '" name="' . $field['name'] . '" value="' . $this->value . '"' . $placeholder . $min . $max .  '" class="' . $field['class'] . '" />' . $after;
		}
	}
}
