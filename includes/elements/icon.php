<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een icon te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Icon extends Element {

	/** Class van icon */
	protected string $icon_class;

	/** Grootte van icon */
	protected int $size = 2;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'icon' => [
				'icon_class' => $this->icon_class,
				'size'       => $this->size,
			],
		];
	}

	/** Zet class van icon */
	public function set_icon_class( string $icon_class ): self {
		$this->icon_class = $icon_class;
		return $this;
	}

	/** Zet grootte van icon */
	public function set_size( int $size ): self {
		$this->size = $size;
		return $this;
	}
}
