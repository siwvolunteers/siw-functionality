<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Helpers\Template;
use SIW\Traits\Class_Assets;

abstract class Widget extends \SiteOrigin_Widget {

	use Class_Assets;

	protected const DEFAULT_TEMPLATE_ID = 'default';

	final protected function get_id(): string {
		$id_base = explode( '\\', static::class );
		return strtolower( end( $id_base ) );
	}

	abstract protected function get_name(): string;

	abstract protected function get_description(): string;

	abstract protected function get_template_id(): string;

	abstract protected function get_dashicon(): string;

	abstract protected function supports_title(): bool;

	abstract protected function supports_intro(): bool;

	final public function __construct() {

		parent::__construct(
			"siw_{$this->get_id()}_widget",
			"SIW: {$this->get_name()}",
			[
				'description'   => $this->get_description(),
				'panels_groups' => [ 'siw' ],
				'panels_icon'   => sprintf( 'dashicons dashicons-%s', $this->get_dashicon() ),
				'has_preview'   => false,
			],
			[],
			[],
			plugin_dir_path( __FILE__ )
		);

		add_filter( 'wpml_siteorigin_modules_to_translate', [ $this, 'register_widget_for_translation' ] );
	}

	//TODO: werkt nog niet voor sections en nested repeaters
	public function register_widget_for_translation( array $widgets ): array {
		$translatable_fields = [];
		$translatable_repeaters = [];

		foreach ( $this->get_widget_form() as $field_id => $field ) {
			if ( in_array( $field['type'], [ 'text', 'textarea', 'tinymce' ], true ) ) {
				$translatable_fields[] = $this->parse_translatable_field( $field_id, $field );
			} elseif ( 'repeater' === $field['type'] ) {
				$translatable_repeaters[ $field_id ] = $this->parse_translatable_repeater( $field_id, $field );
			}
		}

		$widgets[ '\\' . static::class ] = [
			'conditions'     => [ 'widgetType' => '\\' . static::class ],
			'fields'         => $translatable_fields,
			'fields_in_item' => $translatable_repeaters,
		];
		return $widgets;
	}

	protected function parse_translatable_field( string $field_id, array $field ): array {
		return [
			'field'       => $field_id,
			'type'        => $field['label'],
			'editor_type' => match ( $field['type'] ) {
				'tinymce'  => 'VISUAL',
				'textarea' => 'AREA',
				'text'     => 'LINE',
				default    => 'LINE'
			},
		];
	}

	protected function parse_translatable_repeater( string $repeater_id, array $repeater ): array {
		$translatable_fields = [];
		foreach ( $repeater['fields'] as $field_id => $field ) {
			if ( in_array( $field['type'], [ 'text', 'textarea', 'tinymce' ], true ) ) {
				$translatable_fields[] = $this->parse_translatable_field( $field_id, $field );
			}
		}
		return $translatable_fields;
	}

	protected function get_widget_fields(): array {
		return [];
	}

	#[\Override]
	final public function get_widget_form() {

		$widget_form = [];
		if ( $this->supports_title() ) {
			$widget_form['title'] = [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw' ),
			];
		}

		if ( $this->supports_intro() ) {
			$widget_form['intro'] = [
				'type'           => 'tinymce',
				'label'          => __( 'Intro', 'siw' ),
				'rows'           => 5,
				'default_editor' => 'html',
			];
		}
		$widget_form = array_merge(
			$widget_form,
			$this->get_widget_fields()
		);

		return array_filter( $widget_form );
	}


	final protected function get_html_content( array $instance, array $args, array $template_vars, string $css_name ) {

		if ( $this->supports_title() ) {
			$title = $instance['title'] ?? '';
			$title = apply_filters( 'widget_title', $title );

			$template_vars['title'] = $title ? $args['before_title'] . $title . $args['after_title'] : '';
		}
		if ( $this->supports_intro() ) {
			$template_vars['intro'] = $instance['intro'] ?? '';
		}

		$template_id = str_replace( '_', '-', $this->get_template_id() );
		return Template::create()->set_template( "widgets/{$template_id}" )->set_context( $template_vars )->parse_template();
	}
}
