<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\External_Assets\A11Y_Accordion_Tabs;
use SIW\Util\CSS;

/**
 * Class om een accordion te genereren
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Accordion_Tabs extends Repeater {

	/** Asset handle */
	const ASSETS_HANDLE = 'siw-accordion-tabs';

	protected bool $tabs_allowed = false;
	protected bool $start_collapsed = true;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'accordion-tabs';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'panes'           => $this->items,
			'tabs_allowed'    => wp_json_encode( $this->tabs_allowed ),
			'breakpoint'      => CSS::MOBILE_BREAKPOINT,
			'start_collapsed' => wp_json_encode( $this->start_collapsed ),
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
		wp_enqueue_script( A11Y_Accordion_Tabs::get_assets_handle() );
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/accordion-tabs.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/accordion-tabs.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	public function set_tabs_allowed( bool $tabs_allowed ): static {
		$this->tabs_allowed = $tabs_allowed;
		return $this;
	}


	/** {@inheritDoc} */
	protected function initialize() {
		$this->add_class( 'js-tabs' );
	}
}
