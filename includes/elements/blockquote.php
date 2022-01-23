<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een blockquote te genereren
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Blockquote extends Element {

	/** Quote */
	protected string $quote;

	/** Naam */
	protected string $name;
	
	/** Bron/toelichting */
	protected string $source;

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'blockquote';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'quote'  => $this->quote,
			'footer' => ( isset( $this->name ) && isset( $this->source ) ) ?
				[ 'name' => $this->name, 'source' => $this->source ] :
				[]
		];
	}

	public function set_quote( string $quote ) {
		$this->quote = $quote;
		return $this;
	}

	public function set_name( string $name ) {
		$this->name = $name;
		return $this;
	}

	public function set_source( string $source ) {
		$this->source = $source;
		return $this;
	}
}
