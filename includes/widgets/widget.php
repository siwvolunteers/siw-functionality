<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Helpers\Template;

/**
 * SIW Widget base class
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Widget extends \SiteOrigin_Widget {

	/** ID van default Mustache template */
	const DEFAULT_TEMPLATE_ID = 'default';

	/** Geeft ID terug */
	abstract protected function get_id(): string;

	/** Geeft naam terug */
	abstract protected function get_name(): string;

	/** Geeft beschrijving terug */
	abstract protected function get_description(): string;

	/** Geeft Mustache template-ID terug */
	abstract protected function get_template_id(): string;

	/** Geeft dashicon terug */
	abstract protected function get_dashicon(): string;

	/** Constructor */
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

	/** Genereert generieke inhoud van widget */
	final protected function get_html_content( array $instance, array $args, array $template_vars, string $css_name ) {
		$title = $instance['title'] ?? '';
		$title = apply_filters( 'widget_title', $title );

		$template_vars['title'] = $title ? $args['before_title'] . $title . $args['after_title'] : '';

		$template_id = str_replace( '_', '-', $this->get_template_id() );

		return Template::create()->set_template( "widgets/{$template_id}" )->set_context( $template_vars )->parse_template();
	}
}
