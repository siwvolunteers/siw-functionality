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
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
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
			<p><?= esc_html( SIW_Properties::NAME );?></p>
			<p><?= esc_html( SIW_Properties::ADDRESS );?>&nbsp;|&nbsp;<?= esc_html( SIW_POSTAL_CODE );?>&nbsp;<?= esc_html( SIW_CITY );?></p>
			<p><i class="kt-icon-phone3"></i>&nbsp;<?= esc_html( SIW_Properties::PHONE );?>&nbsp;|&nbsp;<i class="kt-icon-envelop"></i>&nbsp;<?= SIW_Formatting::generate_link( "mailto:" . antispambot( SIW_Properties::EMAIL ), antispambot( SIW_Properties::EMAIL ) );?></p>
			<p><i class="kt-icon-clock3"></i>&nbsp;<?php printf( esc_html__( 'Maandag t/m vrijdag %s-%s', 'siw' ), SIW_Properties::OPENING_TIME, SIW_Properties::CLOSING_TIME );?></p>
		</div>
		<div class="siw-social-links clearfix">
			<?php
			$social_networks = siw_get_social_networks('follow');
			foreach ( $social_networks as $network ) {
				echo SIW_Formatting::generate_link(
					$network->get_follow_url(),
					'&shy;',
					[
						'class'               => $network->get_slug(),
						'title'               => $network->get_name(),
						'target'              => '_blank',
						'rel'                 => 'noopener',
						'data-toggle'         => 'tooltip',
						'data-placement'      => 'top',
						'data-original-title' => $network->get_name(),
					],
					"kt-icon-{$network->get_slug()}2"
				);
			}
			?>
		</div>
		<?php

		$html_content = ob_get_clean();
		return $html_content;
	}
}