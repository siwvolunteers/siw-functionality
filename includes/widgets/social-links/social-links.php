<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Social_Network_Context;
use SIW\Elements\Social_Links as Social_Links_Element;

/**
 * Widget Name: SIW: Social links
 * Description: Toont links naar sociale netwerken
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Social_Links extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'social_links';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Social links', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont links naar sociale netwerken', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'share';
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

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => Social_Links_Element::create()->set_context( Social_Network_Context::tryFrom( $instance['context'] ) )->generate(),
		];
	}
}
