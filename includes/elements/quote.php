<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een quote te genereren
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Quote extends Element {

	/** {@inheritDoc} */
	protected string $quote;

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'quote';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'quote' => $this->quote,
			'icon'  => [
				'size'       => 2,
				'icon_class' => 'siw-icon-quote-left',
			],
			
		];
	}

	/** Zet quote */
	public function set_quote( string $quote ) : self {
		$this->quote = $quote;
		return $this;
	}
}