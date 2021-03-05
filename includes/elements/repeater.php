<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;

/**
 * Abstracte klasse voor het maken van een repeater
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Repeater {
	
	/** Items */
	protected array $items;

	/** Init */
	private function __construct() {
		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	/** Creeert repeater */
	public static function create( array $items = [] ) {
		$self = new static();
		$self->items = $items;
		return $self;
	}

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

	/** Genereert repeater */
	public function generate() : string {
		return Template::parse_template(
			"elements/{$this->get_id()}",
			$this->get_template_variables()
		);
	}

	/** Rendert repeater */
	public function render() {
		echo $this->generate();
	}

	/** Geeft id terug */
	abstract protected function get_id() : string;

	/** Parset item*/
	protected function parse_item( array $item ) : array {
		return $item;
	}
	
	/** Geeft default waardes van item terug TODO: abstract maken? */
	protected function get_item_defaults() : array {
		return [];
	}

	/** Geeft template variabelen voor Mustache-template terug */
	abstract protected function get_template_variables() : array;

	/** Voegt scripts toe */
	protected function enqueue_scripts() {}

	/** Voegt scripts toe */
	protected function enqueue_styles() {}
}