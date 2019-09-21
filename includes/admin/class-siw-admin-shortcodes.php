<?php

/**
 * Shortcodes in admin
 * 
 * @package   SIW\Admin
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Admin_Shortcodes {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'admin_init', [ $self, 'add_shortcode_button' ] );
		add_action( 'admin_enqueue_scripts', [ $self, 'localize_script' ] );
	}

	/**
	 * Voegt knop en plugin voor shortcode toe
	 */
	public function add_shortcode_button() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) !== 'true' ) {
			return;
		}

		add_filter( 'mce_external_plugins', [ $this, 'add_tinymce_plugin' ] );
		add_filter( 'mce_buttons', [ $this, 'register_button' ] );
	}

	/**
	 * Maakt alle shortcodes beschikbaar via localize_script
	 */
	public function localize_script() {
		$shortcodes = siw_get_data( 'shortcodes' );
		array_walk( $shortcodes, [ $this, 'format_shortcode' ] );

		$siw_shortcodes = [
			'title'      =>  __( 'SIW Shortcodes', 'siw' ),
			'shortcodes' => $shortcodes,
		];

		//Meeliften op script voor TinyMCE
		wp_localize_script( 'editor', 'siw_shortcodes', $siw_shortcodes );
	}

	/**
	 * Formatteert shortcode voor gebruik in TinyMCE
	 *
	 * @param array $value
	 */
	protected function format_shortcode( &$value ) {
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

	/**
	 * Voegt plugin voor TinyMCE toe
	 *
	 * @param array $plugins
	 * @return array
	 */
	public function add_tinymce_plugin( array $plugins ) {
		$plugins['siw_shortcodes'] = SIW_ASSETS_URL . 'js/siw-admin-shortcodes.js';
		return $plugins;
	}

	/**
	 * Voegt knop voor SIW-shortcodes toe aan TinyMCE
	 *
	 * @param array $buttons
	 * @return array
	 */
	public function register_button( array $buttons ) {
		array_push( $buttons, 'siw_shortcodes' );
		return $buttons;
	}
}