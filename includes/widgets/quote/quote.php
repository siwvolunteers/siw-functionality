<?php
/*
 * 
 * Widget Name: SIW: Quote
 * Description: Toont quote van deelnemer
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met quote
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Widget_Quote extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id ='quote';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'editor-quote';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Quote', 'siw');
		$this->widget_description = __( 'Toont quote van deelnemer', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Ervaringen van deelnemers', 'siw' ),
			],
			'category' => [
				'type'    => 'select',
				'label'   => __( 'Categorie', 'siw'),
				'options' => siw_get_testimonial_quote_categories(),
			]
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) {
		$quote = siw_get_testimonial_quote( $instance['category'] );
		ob_start();
		?>
		<div class="quote">
			<div class="text">
			"<?= esc_html( $quote['quote'] );?>"
			</div>
			<div class="volunteer">
				<span class="name"><?= esc_html( $quote['name'] );?></span>
				<span class="separator">&nbsp;|&nbsp;</span>
				<span class="category"><?= esc_html( $quote['project'] );?></span>
			</div>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	

}