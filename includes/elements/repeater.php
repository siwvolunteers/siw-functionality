<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Abstracte klasse voor het maken van een repeater
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Repeater extends Element {
	
	/** Items */
	protected array $items;

	/** Voegt item toe aan repeater */
	public function add_item( array $item ) {

		$item = \wp_parse_args_recursive(
			$item,
			$this->get_item_defaults(),
		);

		$this->items[] = $this->parse_item( $item );
		return $this;
	}
	
	/** Voegt meerdere items toe aan repeater */
	public function add_items( array $items ) {
		foreach ( $items as $item ) {
			$this->add_item( $item );
		}
		return $this;
	}

	/** Parset item*/
	protected function parse_item( array $item ) : array {
		return $item;
	}
	
	/** Geeft default waardes van item terug TODO: abstract maken? */
	protected function get_item_defaults() : array {
		return [];
	}
}
