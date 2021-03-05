<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een accordion te genereren
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://github.com/AcceDe-Web/accordion
 */
class Accordion extends Repeater {

	/** Versienummer */
	const ACCORDION_VERSION = '1.1.0';

	/** {@inheritDoc} */
	protected function get_id() : string {
		return 'accordion';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'id'    => uniqid(),
			'panes' => $this->items
		];
	}

	/** {@inheritDoc} */
	protected function parse_item( array $item ): array {
		return [
			'id'       => uniqid(),
			'title'    => $item['title'] ?? '',
			'content'  => $item['content'] ?? '',
			'button'   => $item['show_button'] ?
				[ 'url'  => $item['button_url'], 'text' => $item['button_text'] ] :
				[],
		];
	}

	/** {@inheritDoc} */
	protected function get_item_defaults() : array {
		return [
			'title'       => '',
			'content'     => '',
			'show_button' => false,
			'button_text' => '',
			'button_url'  => '',
		];
	}

	/** Voegt scripts toe */
	protected function enqueue_scripts() {
		wp_register_script( 'a11y-accordion', SIW_ASSETS_URL . 'vendor/accordion/accordion.js', [], self::ACCORDION_VERSION, true );
		wp_register_script( 'siw-accordion', SIW_ASSETS_URL . 'js/elements/siw-accordion.js', ['a11y-accordion'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-accordion' );
	}

	/** Voegt styles toe */
	protected function enqueue_styles() {
		wp_register_style( 'siw-accordion', SIW_ASSETS_URL . 'css/elements/siw-accordion.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-accordion' );
	}
}
