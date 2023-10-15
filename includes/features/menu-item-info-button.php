<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Special_Page;

/**
 * Menu item met Infoknop
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Menu_Item_Info_Button extends Base {

	#[Add_Action( 'generate_menu_bar_items' ) ]
	public function add_contact_button_to_menu() {
		// TODO: settings uit Customizer halen?
		printf(
			'<span ><a href="%1$s" class="button">%2$s</a></span>',
			esc_url( get_permalink( siw_get_special_page( Special_Page::CONTACT() ) ) ),
			esc_html__( 'Info aanvragen', 'siw' ),
		);
	}
}
