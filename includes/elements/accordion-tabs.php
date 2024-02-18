<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Breakpoint;
use SIW\External_Assets\A11Y_Accordion_Tabs;

class Accordion_Tabs extends Repeater {

	protected bool $tabs_allowed = false;
	protected bool $start_collapsed = true;

	#[\Override]
	protected function get_template_variables(): array {
		return [
			'panes'           => $this->items,
			'tabs_allowed'    => wp_json_encode( $this->tabs_allowed ),
			'breakpoint'      => Breakpoint::MOBILE->value,
			'start_collapsed' => wp_json_encode( $this->start_collapsed ),
		];
	}

	#[\Override]
	protected function parse_item( array $item ): array {
		return [
			'section_id' => wp_unique_prefixed_id( 'siw-accordion-section-' ),
			'tab_id'     => wp_unique_prefixed_id( 'siw-accordion-tab-' ),
			'title'      => $item['title'] ?? '',
			'content'    => $item['content'] ?? '',
		];
	}

	#[\Override]
	protected function get_item_defaults(): array {
		return [
			'title'   => '',
			'content' => '',
		];
	}

	#[\Override]
	public function enqueue_scripts() {
		wp_enqueue_script( A11Y_Accordion_Tabs::get_asset_handle() );
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}

	public function set_tabs_allowed( bool $tabs_allowed ): static {
		$this->tabs_allowed = $tabs_allowed;
		return $this;
	}

	#[\Override]
	protected function initialize() {
		$this->add_class( 'js-tabs' );
	}
}
