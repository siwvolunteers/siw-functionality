<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een lijst met kolommen te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Breadcrumbs extends Repeater {

	const ASSETS_HANDLE = 'siw-breadcrumbs';

	protected string $current;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'breadcrumbs';
	}

	/** Zet huidige pagina */
	public function set_current( string $current ): static {
		$this->current = $current;
		return $this;
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'current' => $this->current,
			'items'   => $this->items,
		];
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/breadcrumbs.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/breadcrumbs.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

}
