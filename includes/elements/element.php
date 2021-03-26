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

	/** Init */
	protected function __construct() {}

	/** Genereert element */
	public static function create() {
		$self = new static();
		return $self;
	}

	/** Genereert element */
	public function generate() : string {
		$this->enqueue_scripts();
		$this->enqueue_styles();
		return Template::parse_template(
			"elements/{$this->get_id()}",
			$this->get_template_variables()
		);
	}

	/** Rendert repeater */
	public function render() {
		echo $this->generate();
	}

	/** Voegt scripts toe */
	protected function enqueue_scripts() {}

	/** Voegt scripts toe */
	protected function enqueue_styles() {}
}
