<?php
/*
 * 
 * Widget Name: SIW: Contactinformatie
 * Description: Toont contactinformatie.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met contactinformatie
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Properties
 * @uses      SIW_Formatting
 */
class SIW_Widget_Contact extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id = 'contact';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'phone';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Contactinformatie', 'siw');
		$this->widget_description = __( 'Toont contactinformatie', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_forms = [
			'title' => [
				'type'      => 'text',
				'label'     => __( 'Titel', 'siw'),
				'default'   => __( 'Contact', 'siw' ),
			],
		];
		return $widget_forms;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_content( $instance, $args, $template_vars, $css_name ) {
		ob_start();
		?>
		<div class="siw-contact">
			<?php
			echo wpautop( SIW_Formatting::array_to_text(
				[
					esc_html( SIW_Properties::NAME ),
					sprintf( '%s | %s %s', SIW_Properties::ADDRESS, SIW_Properties::POSTCODE, SIW_Properties::CITY ),
					sprintf( '%s %s | %s %s', SIW_Formatting::generate_icon( 'siw-icon-phone', 1 ), SIW_Properties::PHONE, SIW_Formatting::generate_icon( 'siw-icon-envelope', 1 ), SIW_Properties::EMAIL ),
					sprintf( '%s %s', SIW_Formatting::generate_icon( 'siw-icon-clock', 1 ), sprintf( esc_html__( 'Maandag t/m vrijdag %s-%s', 'siw' ), SIW_Properties::OPENING_TIME, SIW_Properties::CLOSING_TIME ) ),
				],
				BR2
				)
			);
			?>
		</div>
		<div class="siw-social-links clearfix">
			<?php
			$social_networks = siw_get_social_networks('follow');
			foreach ( $social_networks as $network ) {
				echo SIW_Formatting::generate_link(
					$network->get_follow_url(),
					SIW_Formatting::generate_icon( $network->get_icon_class(), 1, 'circle' ),
					[
						'class'               => $network->get_slug(),
						'title'               => $network->get_name(),
						'target'              => '_blank',
						'rel'                 => 'noopener',
						'data-toggle'         => 'tooltip',
						'data-placement'      => 'top',
						'data-original-title' => $network->get_name(),
					]
				);
			}
			?>
		</div>
		<?php

		$html_content = ob_get_clean();
		return $html_content;
	}
}