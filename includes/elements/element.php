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

	/** CSS classes van het element */
	protected array $classes;

	/** Geeft type van element terug */
	abstract protected static function get_type(): string;

	/** Geeft template variabelen voor Mustache-template terug */
	abstract protected function get_template_variables() : array;

	/** Geeft uniek id voor element terug terug */
	final protected function get_element_id(): string {
		return $this->element_id;
	}

	/** Geeft standaard css klasse voor element terug */
	final protected static function get_element_class(): string {
		return 'siw-' . static::get_type();
	}

	/** Init */
	final protected function __construct() {
		$this->element_id = wp_unique_id( "siw-{$this::get_type()}-" );
		$this->initialize();
		$this->classes[] = static::get_element_class();
	}

	/** Genereert element */
	final public static function create(): static {
		$self = new static();
		return $self;
	}

	/** Voegt extra css class toe */
	public function add_class( string $class ): static {
		$this->classes[] = sanitize_html_class( $class );
		return $this;
	}

	/** Voegt extra css classes toe */
	public function add_classes( array $classes ): static {
		foreach ( $classes as $class ) {
			$this->classes[] = sanitize_html_class( $class );
		}
		return $this;
	}

	/** Genereert element */
	final public function generate(): string {

		$asset_hook = is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

		if ( did_action( $asset_hook ) > 0 ) {
			$this->enqueue_scripts();
			$this->enqueue_styles();
		} else {
			add_action( $asset_hook, [ $this, 'enqueue_styles' ] );
			add_action( $asset_hook, [ $this, 'enqueue_scripts' ] );
		}

		$template_variables = wp_parse_args(
			array_filter( $this->get_template_variables() ),
			[
				'element' => [
					'id'      => $this->get_element_id(),
					'classes' => implode( ' ', $this->classes ),
					'type'    => static::get_type(),
				],
			]
		);

		return Template::create()
			->set_template( "elements/{$this::get_type()}" )
			->set_context( $template_variables )
			->parse_template();
	}

	/** Rendert repeater */
	final public function render() {
		echo $this->generate(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {}

	/** Voegt scripts toe */
	public function enqueue_styles() {}

	/** Initialiseert element */
	protected function initialize() {}
}
