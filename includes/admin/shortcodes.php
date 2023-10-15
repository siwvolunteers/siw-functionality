<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Shortcodes as SIW_Shortcodes;
use SIW\Traits\Assets_Handle;

/**
 * Shortcodes in admin
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Shortcodes extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_editor' )]
	/** Script toevoegen */
	public function enqueue_script() {

		if ( did_action( 'wp_enqueue_editor' ) > 1 ) {
			return;
		}

		wp_register_script( self::get_assets_handle(), SIW_ASSETS_URL . 'js/admin/siw-shortcodes.js', [], SIW_PLUGIN_VERSION, true );

		// Shortcodes ophalen
		$shortcodes = SIW_Shortcodes::get_shortcodes();
		array_walk( $shortcodes, [ $this, 'format_shortcode' ] );
		$shortcodes = array_values( $shortcodes );

		$siw_shortcodes = [
			'title'      => __( 'SIW Shortcodes', 'siw' ),
			'shortcodes' => $shortcodes,
		];

		wp_localize_script(
			self::get_assets_handle(),
			'siw_shortcodes',
			$siw_shortcodes
		);
		wp_enqueue_script( self::get_assets_handle() );
	}

	/** Formatteert shortcode voor gebruik in TinyMCE */
	protected function format_shortcode( &$value, $key ) {

		// hortcodes zonder parameter verwerken
		if ( is_string( $value ) ) {
			$value = [
				'title' => $value,
			];
		}
		$value['shortcode'] = $key;

		// TODO: klop dit wel?
		$properties = [ 'shortcode', 'title', 'attributes' ];
		$value = array_intersect_key( $value, array_flip( $properties ) );
		if ( isset( $value['attributes'] ) ) {
			$value['attributes'] = array_map( [ $this, 'format_attribute' ], $value['attributes'] );
		}
	}

	/** Formatteert attribute voor gebruik in TinyMCE */
	protected function format_attribute( array $data ): array {
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
					'values' => array_map( [ $this, 'format_options' ], array_keys( $data['options'] ), $data['options'] ),
				];
				break;
			default:
		}
		return $attribute;
	}

	/** Formatteert opties voor gebruik in TinyMCE */
	protected function format_options( string $value, string $label ): array {
		return [
			'value' => $value,
			'text'  => $label,
		];
	}
}
