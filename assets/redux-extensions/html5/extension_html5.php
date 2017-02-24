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
 * @author      Dovy Paukstys (dovy)
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_Extension_html5' ) ) {

    /**
     * Main ReduxFramework custom_field extension class
     *
     * @since       3.1.6
     */
    class ReduxFramework_Extension_html5 extends ReduxFramework {

        // Protected vars
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;

        /**
        * Class Constructor. Defines the args for the extions class
        *
        * @since       1.0.0
        * @access      public
        * @param       array $sections Panel sections.
        * @param       array $args Class constructor arguments.
        * @param       array $extra_tabs Extra panel tabs.
        * @return      void
        */
        public function __construct( $parent ) {

            $this->parent = $parent;
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
            }
            $this->field_name = 'html5';

            self::$theInstance = $this;

            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/'.$this->field_name, array( &$this, 'overload_field_path' ) ); // Adds the local field

        }

		public function _validate_values ( $plugin_options, $field, $sections ){
			$id = $field['id'];
			$value = $plugin_options[ $id ];
			$type = $field['html5'];
			switch ( $type ){
				case 'number':
					//zet opties voor FILDER_VALIDATE_INT
					$options = array();
					if (isset( $field['min'] )){
						$options['min_range'] = $field['min'];
					}
					if (isset( $field['max'] )){
						$options['max_range'] = $field['max'];
					}
					//Controleer waarde
					if (!empty( $value ) && filter_var( $value, FILTER_VALIDATE_INT, array('options'=>$options )) === false) {
						$error_message = __('Dit is geen geldige waarde', 'siw');
					}
					break;
				case 'date':
					//Niet nodig, ongeldige invoer wordt door html5-input al geblokkeerd
					break;
				case 'url':
					if (!empty( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) === false) {
						$error_message = __('Dit is geen geldige url', 'siw');
					}
					break;
				case 'email':
					if (!empty( $value ) && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
						$error_message = __('Dit is geen geldig e-mailadres', 'siw');
					}
					break;
				case 'tel':
					break;
			}

			if(!empty( $error_message )){
				$field['msg'] = $error_message;
				$this->parent->errors[] = $field;
				$plugin_options[$id] = isset( $field['default'] ) ? $field['default'] : '';

			}

			return $plugin_options;
		}

        public function getInstance() {
            return self::$theInstance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path( $field ) {
            return dirname(__FILE__).'/'.$this->field_name.'/field_'.$this->field_name.'.php';
        }

    } // class
} // if
