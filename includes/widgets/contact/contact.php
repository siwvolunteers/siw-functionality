<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;
use SIW\Properties;

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
	function get_template_variables( $instance, $args ) {
		$social_networks = siw_get_social_networks( 'follow' );

		foreach ( $social_networks as $network ) {
			$networks[] = [
				'url' => $network->get_follow_url(),
				'name' => $network->get_name(),
				'label' => sprintf( __( 'Volg ons op %s', 'siw' ), $network->get_name() ),
				'color' => $network->get_color(),
				'icon'  => [
					'icon_class'       => $network->get_icon_class(),
					'size'             => 2,
					'has_background'   => true,
					'background_class' => 'circle',
				],
			];
		}

		return [
				'name'            => Properties::NAME,
				'address'         => Properties::ADDRESS,
				'postcode'        => Properties::POSTCODE,
				'city'            => Properties::CITY,
				'tel_link'        => [
					'phone' => Properties::PHONE_INTERNATIONAL,
					'text'  => Properties::PHONE
				],
				'email_link'      => [
					'email' => Properties::EMAIL,
					'text'  => Properties::EMAIL,
				],
				'whatsapp_link'   => [
					'url'   => add_query_arg( 'phone', Properties::WHATSAPP_FULL, 'https://api.whatsapp.com/send' ),
					'phone' => Properties::WHATSAPP,
					'icon'  => [
						'size'       => 2,
						'icon_class' => 'siw-icon-whatsapp'
					],
				],
				'opening_hours'   => Elements::generate_opening_hours('table'),
				'social_networks' => $networks,
		];
	}
}
