<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Assets\A11Y_Tablist;

/**
 * Class om een tablist te genereren
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Tablist extends Repeater {

	// Constantes voor asset handles
	const ASSETS_HANDLE = 'siw-tablist';


	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'tablist';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'panes' => $this->items,
		];
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/elements/siw-tablist.js', [ A11Y_Tablist::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/siw-tablist.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	/** {@inheritDoc} */
	protected function get_item_defaults(): array {
		return [
			'title'       => '',
			'content'     => '',
			'show_button' => false,
			'button_url'  => '',
			'button_text' => '',
		];
	}

	/** {@inheritDoc} */
	protected function parse_item( array $item ): array {
		return [
			'id'      => wp_unique_id(),
			'title'   => $item['title'] ?? '',
			'content' => $item['content'] ?? '',
			'button'  => $item['show_button'] ?
				[
					'url'  => $item['button_url'],
					'text' => $item['button_text'],
				] :
				[],
		];
	}

}
