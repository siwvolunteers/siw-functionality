<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Helpers\Template;

/**
 * Class om een element te genereren
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Element {

	/** Uniek ID van element */
	protected string $element_id;

	/** Geeft type van element terug */
	abstract protected static function get_type(): string;

	/** Geeft template variabelen voor Mustache-template terug */
	abstract protected function get_template_variables() : array;

	/** Geeft uniek id voor element terug terug */
	protected function get_element_id(): string {
		return $this->element_id;
	}

	/** Geeft standaard css klasse voor element terug */
	protected static function get_element_class(): string {
		return "siw-" . static::get_type();
	}

	/** Init */
	protected function __construct() {
		$this->element_id = wp_unique_id( "siw-{$this::get_type()}-" );
		$this->initialize();
	}

	/** Genereert element */
	public static function create() {
		$self = new static();
		return $self;
	}

	/** Genereert element */
	public function generate(): string {
		$this->enqueue_scripts();
		$this->enqueue_styles();

		$template_variables = wp_parse_args(
			$this->get_template_variables(),
			[
				'element' => [
					'id'    => $this->get_element_id(),
					'class' => static::get_element_class(),
					'type'  => static::get_type(),
				],
			]
		);

		return Template::create()
			->set_template( "elements/{$this::get_type()}" )
			->set_context( $template_variables )
			->parse_template();
	}

	/** Rendert repeater */
	public function render() {
		echo $this->generate();
	}

	/** Voegt scripts toe */
	protected function enqueue_scripts() {}

	/** Voegt scripts toe */
	protected function enqueue_styles() {}

	/** Initialiseert element */
	protected function initialize() {}
}
