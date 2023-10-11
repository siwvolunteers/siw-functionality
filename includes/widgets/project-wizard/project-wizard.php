<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Project_Wizard as Project_Wizard_Element;

/**
 * Widget met keuzehulp
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Keuzehulp
 * Description: Toont keuzehulp
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Project_Wizard extends Widget {

	protected \RW_Meta_Box $meta_box;

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'project_wizard';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Keuzehulp', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont keuzehulp', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'lightbulb';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => Project_Wizard_Element::create()->generate(),
		];
	}
}
