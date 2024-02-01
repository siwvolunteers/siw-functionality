<?php declare(strict_types=1);

namespace SIW\Elements;

class Quote extends Element {

	#[\Override]
	protected string $quote;

	#[\Override]
	protected function get_template_variables(): array {
		return [
			'quote' => $this->quote,
		];
	}

	public function set_quote( string $quote ): self {
		$this->quote = $quote;
		return $this;
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
