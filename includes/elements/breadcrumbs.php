<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een lijst met kolommen te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Breadcrumbs extends Repeater {

	protected string $current;

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
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/elements/breadcrumbs.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/elements/breadcrumbs.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}

}
