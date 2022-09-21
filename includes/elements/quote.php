<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een quote te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Quote extends Element {

	const ASSETS_HANDLE = 'siw-quote';

	/** {@inheritDoc} */
	protected string $quote;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'quote';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'quote' => $this->quote,
		];
	}

	/** Zet quote */
	public function set_quote( string $quote ): self {
		$this->quote = $quote;
		return $this;
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/quote.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/quote.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
