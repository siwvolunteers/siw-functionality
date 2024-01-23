<?php declare(strict_types=1);

namespace SIW\Elements;

class Quote extends Element {

	/** {@inheritDoc} */
	protected string $quote;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'quote' => $this->quote,
		];
	}

	public function set_quote( string $quote ): self {
		$this->quote = $quote;
		return $this;
	}

	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
