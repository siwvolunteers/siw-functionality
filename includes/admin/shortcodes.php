<?php

namespace SIW\Admin;

use SIW\Core\Shortcodes as SIW_Shortcodes;

/**
 * Shortcodes in admin
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Shortcodes {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_editor', [ $self, 'enqueue_script'] );
	}

	/**
	 * Script toevoegen
	 */
	public function enqueue_script() {

		//FIXME: workaround voor black studio tinymce
		if ( did_action( 'wp_enqueue_editor' ) > 1 ) {
			return;
		}

		wp_register_script( 'siw-admin-shortcodes', SIW_ASSETS_URL . 'js/admin/siw-shortcodes.js', [], SIW_PLUGIN_VERSION, true );
		
		//Shortcodes ophalen
		$shortcodes = SIW_Shortcodes::get_shortcodes();
		array_walk( $shortcodes, [ $this, 'format_shortcode' ] );
		$shortcodes = array_values( $shortcodes );

		$siw_shortcodes = [
			'title'      => __( 'SIW Shortcodes', 'siw' ),
			'shortcodes' => $shortcodes,
		];

		wp_localize_script(
			'siw-admin-shortcodes',
			'siw_shortcodes',
			$siw_shortcodes
		);
		wp_enqueue_script( 'siw-admin-shortcodes' );
	}

	/**
	 * Formatteert shortcode voor gebruik in TinyMCE
	 *
	 * @param array $value
	 */
	protected function format_shortcode( &$value, $key ) {

		//Shortcodes zonder parameter verwerken
		if ( is_string( $value ) ) {
			$value = [
				'title' => $value
			];
		}
		$value['shortcode'] = $key;

		//TODO: klop dit wel?
		$properties = ['shortcode', 'title', 'attributes'];
		$value = array_intersect_key( $value, array_flip( $properties ) );
		if ( isset( $value['attributes'] ) ) {
			$value['attributes'] = array_map( [ $this, 'format_attribute'], $value['attributes'] );
		}
	}

	/**
	 * Formatteert attribute voor gebruik in TinyMCE
	 *
	 * @param array $data
	 * @return array
	 */
	protected function format_attribute( array $data ) {
		switch ( $data['type'] ) {
			case 'text':
				$attribute = [
					'type'  => 'textbox',
					'name'  => $data['attr'],
					'label' => $data['title'],
				];
				break;
			case 'select':
				$attribute = [
					'type'   => 'listbox',
					'name'   => $data['attr'],
					'label'  => $data['title'],
					'values' => array_map( [ $this, 'format_options'], array_keys( $data['options'] ), $data['options'] ),
				];
				break;
			default:
		}
		return $attribute;
	}

	/**
	 * Formatteert opties voor gebruik in TinyMCE
	 *
	 * @param string $value
	 * @param string $label
	 * @return array
	 */
	protected function format_options( string $value, string $label ) {
		return [
			'value' => $value,
			'text'  => $label,
		];
	}
}
