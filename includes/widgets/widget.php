<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Core\Template;

/**
 * SIW Widget base class
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Widget extends \SiteOrigin_Widget {

	/** ID van widget */
	protected string $widget_id;

	/** Naam van widget */
	protected string $widget_name;

	/** Beschrijving van widget */
	protected string $widget_description = '';

	/** Icon-class van widget voor pagebuilder */
	protected string $widget_dashicon ='admin-generic';

	/** Control opties van widget */
	protected array $widget_control_options = [];

	/** Formuliervelden van widget */
	protected array $widget_fields = [];

	/** Geeft aan of de widget het default template gebruikt */
	protected bool $use_default_template = false;

	/** Constructor */
	public function __construct() {

		$this->set_widget_properties();

		parent::__construct(
			"siw_{$this->widget_id}_widget",
			"SIW: {$this->widget_name}",
			[
				'description'   => $this->widget_description,
				'panels_groups' => [ 'siw' ],
				'panels_icon'   => sprintf( 'dashicons dashicons-%s', $this->widget_dashicon ),
				'has_preview'   => false,
			],
			$this->widget_control_options,
			$this->widget_fields,
			plugin_dir_path( __FILE__ )
		);
	}

	/**
	 * Zet eigenschappen van widget
	 * 
	 * - Naam
	 * - Beschrijving
	 * @todo splitsen in get_name en get_description
	 */
	abstract protected function set_widget_properties();

	/** Genereert generieke inhoud van widget */
	protected function get_html_content( array $instance, array $args, array $template_vars, string $css_name ) { 
		$title = $instance['title'] ?? '';
		$title = apply_filters( 'widget_title', $title );
		
		$template_vars['title'] = $title ? $args['before_title'] . $title . $args['after_title'] : '';

		$template_id = $this->use_default_template ? 'default' : str_replace( '_', '-', $this->widget_id );

		return Template::parse_template( "widgets/{$template_id}", $template_vars );
	}
}
