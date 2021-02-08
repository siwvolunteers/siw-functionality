<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Widget_Style_Fields as Widget_Style_Fields_Interface;

/**
 * Extra Design-opties voor Page Builder
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Design implements Widget_Style_Fields_Interface {

	/** style field voor uitlijning widget title */
	const STYLE_FIELD_WIDGET_TITLE_ALIGN = 'siw_widget_title_align';

	/**
	 * {@inheritDoc}
	 */
	public function add_style_fields( array $fields ) : array {
		$fields[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] = [
			'name'     => __( 'Uitlijning widget titel', 'siw' ),
			'type'     => 'select',
			'group'    => 'design',
			'priority' => 1,
			'options'  => [
				''       => __( 'Standaard', 'siw' ),
				'left'   => __( 'Links', 'siw' ),
				'center' => __( 'Midden', 'siw' ),
				'right'  => __( 'Rechts', 'siw' ),
			]
		];
		return $fields;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array {
		if ( ! isset( $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] ) || '' == $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] ) {
			return $style_attributes;
		}
		$style_attributes['class'][] = sprintf( 'widget-title-%s', $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] );
		return $style_attributes;
	}
}
