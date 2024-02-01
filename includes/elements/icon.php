<?php declare(strict_types=1);

namespace SIW\Elements;

class Icon extends Element {

	protected string $icon_class;
	protected int $size = 2;

	#[\Override]
	protected function get_template_variables(): array {
		return [
			'icon' => [
				'icon_class' => $this->icon_class,
				'size'       => $this->size,
			],
		];
	}

	public function set_icon_class( string|\BackedEnum $icon_class ): self {
		if ( is_a( $icon_class, \BackedEnum::class ) ) {
			$icon_class = $icon_class->value;
		}

		$this->icon_class = $icon_class;
		return $this;
	}

	public function set_size( int $size ): self {
		$this->size = $size;
		return $this;
	}
}
