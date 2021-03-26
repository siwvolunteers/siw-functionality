<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Social_Network;
use SIW\Elements\Table;
use SIW\Properties;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Contactinformatie
 * Description: Toont contactinformatie.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Contact extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'contact';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Contactinformatie', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont contactinformatie', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'phone';
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {
		$social_networks = \siw_get_social_networks( Social_Network::FOLLOW );

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
				'opening_hours'   => Table::create()->add_items( siw_get_opening_hours())->generate(),
				'social_networks' => $networks,
		];
	}
}
