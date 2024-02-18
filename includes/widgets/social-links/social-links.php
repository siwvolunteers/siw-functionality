<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Icons\Dashicons;
use SIW\Data\Social_Network_Context;
use SIW\Elements\Social_Links as Social_Links_Element;

/**
 * Widget Name: SIW: Social links
 * Description: Toont links naar sociale netwerken
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Social_Links extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Social links', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont links naar sociale netwerken', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::SHARE;
	}

	#[\Override]
	public function get_widget_fields(): array {
		$widget_form = [
			'context' => [
				'type'    => 'radio',
				'label'   => __( 'Context', 'siw' ),
				'default' => Social_Network_Context::FOLLOW->value,
				'options' => Social_Network_Context::list(),
			],
		];
		return $widget_form;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => Social_Links_Element::create()->set_context( Social_Network_Context::tryFrom( $instance['context'] ) )->generate(),
		];
	}
}
