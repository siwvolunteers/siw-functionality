<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een blockquote te genereren
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Blockquote extends Element {

	const ASSETS_HANDLE = 'siw-blockquote';

	/** Quote */
	protected string $quote;

	/** Naam */
	protected string $name;

	/** Bron/toelichting */
	protected string $source;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'blockquote';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'quote'  => $this->quote,
			'footer' => ( isset( $this->name ) && isset( $this->source ) ) ?
				[
					'name'   => $this->name,
					'source' => $this->source,
				] :
				[],
		];
	}

	/** Zet de quote */
	public function set_quote( string $quote ): self {
		$this->quote = $quote;
		return $this;
	}

	/** Zet de naam */
	public function set_name( string $name ): self {
		$this->name = $name;
		return $this;
	}

	/** Zet de bron */
	public function set_source( string $source ): self {
		$this->source = $source;
		return $this;
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/blockquote.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/blockquote.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
