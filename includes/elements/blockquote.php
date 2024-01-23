<?php declare(strict_types=1);

namespace SIW\Elements;

class Blockquote extends Element {

	protected string $quote;
	protected string $name;
	protected string $source;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'quote'  => $this->quote,
			'footer' => ( isset( $this->name ) && isset( $this->source ) ) ?
				[
					'name'   => $this->name,
					'source' => $this->source,
				] :
				[],
		];
	}

	public function set_quote( string $quote ): self {
		$this->quote = $quote;
		return $this;
	}

	public function set_name( string $name ): self {
		$this->name = $name;
		return $this;
	}

	public function set_source( string $source ): self {
		$this->source = $source;
		return $this;
	}

	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
