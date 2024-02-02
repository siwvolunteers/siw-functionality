<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Icons\Social_Icons;
use SIW\Elements\Icon;
use SIW\Properties;

/**
 * Widget Name: SIW: Contactinformatie
 * Description: Toont contactinformatie.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Contact extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Contactinformatie', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont contactinformatie', 'siw' );
	}

	#[\Override]
	protected function get_template_id(): string {
		return $this->get_id();
	}

	#[\Override]
	protected function get_dashicon(): string {
		return 'phone';
	}

	#[\Override]
	protected function supports_title(): bool {
		return true;
	}

	#[\Override]
	protected function supports_intro(): bool {
		return true;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {
		return [
			'name'          => Properties::NAME,
			'address'       => Properties::ADDRESS,
			'postcode'      => Properties::POSTCODE,
			'city'          => Properties::CITY,
			'tel_link'      => [
				'full' => Properties::PHONE_INTERNATIONAL,
				'text' => Properties::PHONE,
			],
			'email'         => Properties::EMAIL,
			'whatsapp_link' => [
				'url'  => add_query_arg( 'phone', Properties::WHATSAPP_FULL, 'https://api.whatsapp.com/send' ),
				'text' => Properties::WHATSAPP,
				'icon' => Icon::create()->set_icon_class( Social_Icons::WHATSAPP )->set_size( 3 )->generate(),
			],
		];
	}
}
