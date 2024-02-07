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
			[], // control_options,
			[], // form_options
			plugin_dir_path( __FILE__ )
		);
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
