<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\List_Columns as List_Element;
use SIW\Util\CSS;

/**
 * Widget met lijst
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Lijst
 * Description: Toont lijst
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class List_Columns extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'list';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Lijst', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont lijst', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'editor-ul';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function get_widget_fields(): array {
		$prefix = 'data:image/svg+xml;charset=utf-8,';
		$svg = "<svg width='1em' height='1em' viewBox='0 0 1 1' fill='none' xmlns='http://www.w3.org/2000/svg'><rect width='1' height='1' fill='%s'/></svg>";

		$color_options = array_map(
			fn( array $color ):array  => [
				'value' => $color['slug'],
				'image' => $prefix . rawurlencode( sprintf( $svg, $color['color'] ) ),
				'label' => $color['name'],
			],
			CSS::get_colors()
		);
		$color_options = array_column( $color_options, null, 'value' );

		$widget_form = [
			'columns' => [
				'type'    => 'slider',
				'label'   => __( 'Aantal kolommen', 'siw' ),
				'default' => 1,
				'min'     => 1,
				'max'     => 4,
			],
			'style'   => [
				'type'   => 'section',
				'label'  => __( 'Stijl', 'siw' ),
				'hide'   => true,
				'fields' => [
					'list_style_type'  => [
						'type'    => 'radio',
						'label'   => __( 'Lijst stijl', 'siw' ),
						'options' => [
							''       => __( 'Standaard', 'siw' ),
							'disc'   => '&#x2022;',
							'circle' => '&#x25E6;',
							'square' => '&#x25AA;',
							'check'  => '&#x2713;',
						],

					],
					'set_marker_color' => [
						'type'          => 'checkbox',
						'label'         => __( 'Zet kleur voor marker', 'siw' ),
						'default'       => false,
						'state_emitter' => [
							'callback' => 'conditional',
							'args'     => [
								'marker_color[show]: val',
								'marker_color[hide]: ! val',
							],
						],
					],
					'marker_color'     => [
						'type'          => 'image-radio',
						'label'         => __( 'Kleur', 'siw' ),
						'options'       => $color_options,
						'state_handler' => [
							'marker_color[show]' => [ 'show' ],
							'marker_color[hide]' => [ 'hide' ],
						],
					],
				],
			],
			'items'   => [
				'type'       => 'repeater',
				'label'      => __( 'Items', 'siw' ),
				'item_name'  => __( 'Item', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='item']",
					'update_event' => 'change',
					'value_method' => 'val',
				],
				'fields'     => [
					'item' => [
						'type' => 'text',
					],
				],

			],
		];
			return $widget_form;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		if ( ! isset( $instance['items'] ) || empty( $instance['items'] ) ) {
			return [];
		}

		$list = List_Element::create()
			->set_columns( (int) $instance['columns'] )
			->add_items( array_column( $instance['items'], 'item' ) )
			->set_list_style_type( $instance['style']['list_style_type'] ?? '' )
			->set_marker_color( $instance['style']['set_marker_color'] ? $instance['style']['marker_color'] : '' );

		return [
			'content' => $list->generate(),
		];
	}
}
