<?php declare(strict_types=1);

namespace SIW\Elements;

abstract class Repeater extends Element {

	protected array $items;

	final public function add_item( $item ): static {

		if ( is_array( $item ) ) {
			$item = \wp_parse_args_recursive(
				$item,
				$this->get_item_defaults(),
			);
			$this->items[] = $this->parse_item( $item );
		}

		if ( is_scalar( $item ) ) {
			$this->items[] = $item;
		}
		return $this;
	}

	final public function add_items( array $items ): static {
		foreach ( $items as $item ) {
			$this->add_item( $item );
		}
		return $this;
	}

	protected function parse_item( array $item ): array {
		return $item;
	}

	protected function get_item_defaults(): array {
		return [];
	}
}
