<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een tablist te genereren
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://github.com/AcceDe-Web/tablist
 */
class Tablist extends Repeater {
	
	// Constantes voor asset handles
	const SCRIPT_HANDLE = 'siw-tablist';
	const STYLE_HANDLE = 'siw-tablist';
	const TABLIST_SCRIPT_HANDLE = 'a11y-tablist';

	/** Versienummer */
	const TABLIST_VERSION = '2.0.1';

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
	protected function enqueue_scripts() {
		wp_register_script( self::TABLIST_SCRIPT_HANDLE, SIW_ASSETS_URL . 'vendor/tablist/tablist.js', [], self::TABLIST_VERSION, true );
		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'js/elements/siw-tablist.js', [self::TABLIST_SCRIPT_HANDLE], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	/** Voegt styles toe */
	protected function enqueue_styles() {
		wp_register_style( self::STYLE_HANDLE, SIW_ASSETS_URL . 'css/elements/siw-tablist.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::STYLE_HANDLE );
	}

	/** {@inheritDoc} */
	protected function get_item_defaults(): array {
		return [ 
			'title'       => '',
			'content'     => '',
			'show_button' => false,
			'button_url'  => '',
			'button_text' => ''
		];
	}

	/** {@inheritDoc} */
	protected function parse_item( array $item ): array {
		return [
			'id'       => wp_unique_id(),
			'title'    => $item['title'] ?? '',
			'content'  => $item['content'] ?? '',
			'button'   => $item['show_button'] ?
				[ 'url'  => $item['button_url'], 'text' => $item['button_text'] ] :
				[],
		];
	}

}
