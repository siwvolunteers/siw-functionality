<?php declare(strict_types=1);

namespace SIW\Elements;

class Table extends Repeater {

	protected array $header = [];
	protected array $footer = [];

	#[\Override]
	protected function get_template_variables(): array {
		return [
			'rows'       => $this->items,
			'has_header' => ! empty( $this->header ),
			'header'     => $this->header,
			'has_footer' => ! empty( $this->footer ),
			'footer'     => $this->footer,
		];
	}

	public function set_header( array $header ): self {
		$this->header = $header;
		return $this;
	}

	public function set_footer( array $footer ): self {
		$this->footer = $footer;
		return $this;
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
