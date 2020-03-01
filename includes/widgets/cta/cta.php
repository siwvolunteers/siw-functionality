<?php

namespace SIW\Widgets;

use SIW\i18n;
use SIW\HTML;
use SIW\Util;

/**
 * Widget met Call to Action
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data 
 * Widget Name: SIW: CTA
 * Description: Toont call to action
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class CTA extends Widget {

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
				'options' => Util::get_pages(), 
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
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) {
		ob_start();
		?>
		<div class="title" style="text-align:<?= esc_attr( $instance['align'] ); ?>">
			<?= sprintf( '<%s>%s</%s>', esc_attr( $instance['heading'] ), esc_html( $instance['headline'] ), esc_attr( $instance['heading'] ) );?>
		</div>
		<div class="link" style="text-align:<?= esc_attr( $instance['align'] ); ?>">
			<?= HTML::generate_link( i18n::get_translated_page_url( $instance['button_page'] ), $instance['button_text'], [ 'class' => 'kad-btn' ] ); ?>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}
}
