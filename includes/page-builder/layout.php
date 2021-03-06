<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Row_Style_Fields as Row_Style_Fields_Interface;

/**
 * Layout-opties voor Page Builder
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.?
 */
class Layout implements Row_Style_Fields_Interface {

	/** style field voor Rij layout */
	const STYLE_FIELD_ROW_STRETCH = 'siw_row_stretch';

	/**
	 * {@inheritDoc}
	 */
	public function add_style_fields( array $fields ) : array {
		unset( $fields['row_stretch']);
		$fields[ self::STYLE_FIELD_ROW_STRETCH ] = [
			'name'     => __( 'Rij lay-out', 'siw' ),
			'type'     => 'select',
			'group'    => 'layout',
			'priority' => 10,
			'options'  => [
				''                      => __( 'Standaard', 'siw' ),
				'full_width_background' => __( 'Volledige breedte (achtergrond)', 'siw' ),
				'full_width'            => __( 'Volledige breedte', 'siw' ),
			]
		];
		return $fields;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array {
		if ( ! isset( $style_args[ self::STYLE_FIELD_ROW_STRETCH ] ) ) {
			return $style_attributes;
		}

		if ( 'full_width_background' == $style_args[ self::STYLE_FIELD_ROW_STRETCH ] ) {
			$style_attributes['class'][] = 'row-full-width-background';
		}
		elseif ( 'full_width' == $style_args[ self::STYLE_FIELD_ROW_STRETCH ] ) {
			$style_attributes['class'][] = 'row-full-width';
		}
		return $style_attributes;
	}
}
