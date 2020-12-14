<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;
use SIW\Properties;
use SIW\Util\Links;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Contactinformatie
 * Description: Toont contactinformatie.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Contact extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id = 'contact';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'phone';

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

	public function get_content( array $instance, array $args, array $template_vars, string $css_name ) : string {
		return '';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_template_parameters( array $instance, array $args, array $template_vars, string $css_name ) : array {
		$social_networks = siw_get_social_networks( 'follow' );
		foreach ( $social_networks as $network ) {
			$networks[] = Links::generate_icon_link(
				$network->get_follow_url(),
				[
					'class'      => $network->get_icon_class(),
					'background' => 'circle'
				],
				[
					'class'               => $network->get_slug(),
					'title'               => $network->get_name(),
					'target'              => '_blank',
					'rel'                 => 'noopener external',
					'aria-label'          => sprintf( esc_attr__( 'Volg ons op %s', 'siw' ), $network->get_name() ),
					'data-balloon-pos'    => 'up',
					'data-original-title' => $network->get_name(),
					'style'               => '--hover-color: ' . $network->get_color(),
				],
			);
		}

		return [
				'name'            => Properties::NAME,
				'address'         => Properties::ADDRESS,
				'postcode'        => Properties::POSTCODE,
				'city'            => Properties::CITY,
				'tel_link'        => Links::generate_tel_link( Properties::PHONE_INTERNATIONAL, Properties::PHONE ),
				'email_link'      => Links::generate_mailto_link( Properties::EMAIL ),
				'whatsapp_link'   => Links::generate_whatsapp_link( Properties::WHATSAPP_FULL, Properties::WHATSAPP ),
				'opening_hours'   => Elements::generate_opening_hours('table'),
				'social_networks' => $networks,
		];
	}
}
