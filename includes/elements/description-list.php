<?php declare(strict_types=1);

namespace SIW\Elements;

class Description_List extends Repeater {
	#[\Override]
	protected function get_template_variables(): array {
		return [
			'items' => $this->items,
		];
	}

	#[\Override]
	protected function get_item_defaults(): array {
		return [
			'term'        => '',
			'description' => '',
		];
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
