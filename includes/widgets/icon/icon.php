<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Icon as Icon_Element;

/**
 * Widget met icon
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Icon
 * Description: Toont icon
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Icon extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'icon';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Icon', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont icon', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'info-outline';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return false;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return false;
	}

	/** {@inheritDoc} */
	protected function get_widget_fields(): array {
		$widget_fields = [
			'icon'           => [
				'type'  => 'icon',
				'label' => __( 'Icoon', 'siw' ),
			],
			'has_background' => [
				'type'    => 'checkbox',
				'label'   => __( 'Toon achtergrond', 'siw' ),
				'default' => true,
			],
			'size'           => [
				'type'    => 'slider',
				'label'   => __( 'Grootte', 'siw' ),
				'default' => 4,
				'min'     => 1,
				'max'     => 10,
			],
		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => Icon_Element::create()
				->set_icon_class( $instance['icon'] )
				->set_size( (int) $instance['size'] )
				->generate(),
		];
	}
}
