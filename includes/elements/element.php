<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Helpers\Template;
use SIW\Traits\Class_Assets;

abstract class Element {

	use Class_Assets;

	protected string $element_id;
	protected array $classes;

	final protected static function get_type(): string {
		$class_name_components = explode( '\\', static::class );
		return str_replace(
			'_',
			'-',
			strtolower( end( $class_name_components ) )
		);
	}

	abstract protected function get_template_variables(): array;

	final protected function get_element_id(): string {
		return $this->element_id;
	}

	final protected static function get_element_class(): string {
		return 'siw-' . static::get_type();
	}

	final protected function __construct() {
		$this->element_id = wp_unique_prefixed_id( "siw-{$this::get_type()}-" );
		$this->initialize();
		$this->classes[] = static::get_element_class();
	}

	final public static function create(): static {
		$self = new static();
		return $self;
	}

	public function add_class( string $css_class ): static {
		$this->classes[] = sanitize_html_class( $css_class );
		return $this;
	}

	public function add_classes( array $classes ): static {
		foreach ( $classes as $class ) {
			$this->add_class( $class );
		}
		return $this;
	}

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

	final public function render() {
		echo $this->generate(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function enqueue_scripts() {}

	public function enqueue_styles() {}

	protected function initialize() {}
}
