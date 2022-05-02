<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Assets\A11Y_Accordion;

/**
 * Class om een accordion te genereren
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Accordion extends Repeater {

	/** Asset handle */
	const ASSETS_HANDLE = 'siw-accordion';

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'accordion';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'panes' => $this->items,
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

	/** {@inheritDoc} */
	protected function get_item_defaults(): array {
		return [
			'title'       => '',
			'content'     => '',
			'show_button' => false,
			'button_text' => '',
			'button_url'  => '',
		];
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/elements/siw-accordion.js', [ A11Y_Accordion::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/siw-accordion.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
