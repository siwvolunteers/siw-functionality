<?php
/*
 * 
 * @widget_data 
 * Widget Name: SIW: CTA
 * Description: Toont call to action
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met Call to Action
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 * @uses      SIW_i18n
 * @uses      SIW_Util
 */
class SIW_Widget_CTA extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='cta';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'megaphone';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'CTA', 'siw');
		$this->widget_description = __( 'Toont call to action', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'headline' => [
				'type'    => 'text',
				'label'   => __( 'Headline', 'siw'),
			],
			'heading' => [
				'type'  => 'radio',
				'label' => __( 'Heading', 'siw'),
				'options' => [
					'h2' => 'h2',
					'h4' => 'h4',
				],
			],
			'button_text' => [
				'type'    => 'text',
				'label'   => __( 'Tekst voor knop', 'siw'),
			],
			'button_page' => [
				'type'    => 'select',
				'label'   => __( 'Pagina voor knop', 'siw' ),
				'prompt'  => __( 'Selecteer een pagina', 'siw' ),
				'options' => SIW_Util::get_pages(), 
			],
			'align' => [
				'type' => 'select',
				'label' => __( 'Uitlijning', 'siw'),
				'options' => [
					'left'   => __( 'Links', 'siw' ),
					'center' => __( 'Midden', 'siw' ),
					'right'  => __( 'Rechts', 'siw' ),
				],
				'default' => 'center',
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) {
		ob_start();
		?>
		<div class="title" style="text-align:<?= esc_attr( $instance['align'] ); ?>">
			<?= sprintf( '<%s>%s</%s>', esc_attr( $instance['heading'] ), esc_html( $instance['headline'] ), esc_attr( $instance['heading'] ) );?>
		</div>
		<div class="link" style="text-align:<?= esc_attr( $instance['align'] ); ?>">
			<?= SIW_Formatting::generate_link( SIW_i18n::get_translated_page_url( $instance['button_page'] ), $instance['button_text'], [ 'class' => 'kad-btn' ] ); ?>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}
}
