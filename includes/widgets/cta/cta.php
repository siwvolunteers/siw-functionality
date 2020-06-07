<?php

namespace SIW\Widgets;

use SIW\i18n;
use SIW\Util;
use SIW\Util\Links;

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
		<div style="text-align:<?php echo esc_attr( $instance['align'] ); ?>">
			<div class="headline">
				<?php echo esc_html( $instance['headline'] );?>
			</div>
			<div class="button">
				<?php echo Links::generate_button_link( i18n::get_translated_page_url( $instance['button_page'] ), $instance['button_text'] ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
