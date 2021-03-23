<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;

/**
 * Class om een element te genereren
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Element {

	/** Geeft id terug */
	abstract protected function get_id() : string;

	/** Geeft template variabelen voor Mustache-template terug */
	abstract protected function get_template_variables() : array;

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
}
