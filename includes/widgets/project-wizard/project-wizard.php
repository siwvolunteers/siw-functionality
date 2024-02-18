<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Icons\Dashicons;
use SIW\Elements\Project_Wizard as Project_Wizard_Element;

/**
 * Widget Name: SIW: Keuzehulp
 * Description: Toont keuzehulp
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Project_Wizard extends Widget {

	protected \RW_Meta_Box $meta_box;

	#[\Override]
	protected function get_name(): string {
		return __( 'Keuzehulp', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont keuzehulp', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::LIGHTBULB;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => Project_Wizard_Element::create()->generate(),
		];
	}
}
