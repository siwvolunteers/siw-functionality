<?php declare(strict_types=1);

namespace SIW\Widgets;

use Mustache_Template;
use SIW\Core\Template;

/**
 * SIW Widget base class
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Widget extends \SiteOrigin_Widget {

	/**
	 * ID van widget
	 */
	protected string $widget_id;

	/**
	 * Naam van widget
	 */
	protected string $widget_name;

	/**
	 * Beschrijving van widget
	 */
	protected string $widget_description = '';

	/**
	 * Icon-class van widget voor pagebuilder
	 */
	protected string $widget_dashicon ='admin-generic';

	/**
	 * Control opties van widget
	 */
	protected array $widget_control_options = [];

	/**
	 * Formuliervelden van widget
	 */
	protected array $widget_fields = [];

	/**
	 * Geeft aan of de wiget een eigen Mustache template heeft
	 */
	protected bool $has_template = true;

	/**
	 * Template
	 */
	protected Mustache_Template $template;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->set_widget_properties();

		//Template laden
		if ( $this->has_template ) {
			$template_id = str_replace( '_', '-', $this->widget_id );
			$this->template = Template::get_template( "widgets/{$template_id}" );
		}
		else {
			$this->template = Template::get_template('content');
		}

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
	 */
	abstract protected function set_widget_properties();

	/**
	 * Geeft parameters voor Mustache template terug
	 *
	 * @param array $instance
	 * @param array $args
	 * @param array $template_vars
	 * @param string $css_name
	 * @return string
	 */
	protected function get_template_parameters( array $instance, array $args, array $template_vars, string $css_name ) : array {
		return [];
	}

	/**
	 * Genereert generieke inhoud van widget
	 *
	 * @param array $instance
	 * @param array $args
	 * @param array $template_vars
	 * @param string $css_name
	 * @return string
	 */
	protected function get_html_content( array $instance, array $args, array $template_vars, string $css_name ) { 
		$title = $instance['title'] ?? '';
		$title = apply_filters( 'widget_title', $title );
		
		ob_start();
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}?>
		<div class="content">
			<?php
				echo $this->template->render( $this->get_template_parameters( $instance, $args, $template_vars, $css_name ));
			?>
		</div>
		<?php
		$html_content = ob_get_clean();
		return $html_content;
	}
}
