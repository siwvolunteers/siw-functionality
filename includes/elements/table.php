<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een lijst met kolommen te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Table extends Repeater {

	const ASSETS_HANDLE = 'siw-table';

	/** Inhoud voor header */
	protected array $header = [];

	/** Inhoud voor footer */
	protected array $footer = [];

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'table';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'rows'       => $this->items,
			'has_header' => ! empty( $this->header ),
			'header'     => $this->header,
			'has_footer' => ! empty( $this->footer ),
			'footer'     => $this->footer,
		];
	}

	/** Zet headers van tabel */
	public function set_header( array $header ): self {
		$this->header = $header;
		return $this;
	}

	/** Zet footer van tabel */
	public function set_footer( array $footer ): self {
		$this->footer = $footer;
		return $this;
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/table.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/table.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
