<?php

namespace SIW\Page_Builder;

/**
 * Layout-opties voor Page Builder
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.?
 */
class Layout {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! class_exists( '\SiteOrigin_Panels' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'siteorigin_panels_row_style_fields', [ $self, 'add_row_style_fields'] );
		add_filter( 'siteorigin_panels_row_style_attributes', [ $self, 'add_row_style_attributes'], 10, 2 );
	}

	/**
	 * Voegt opties voor rij toe
	 *
	 * @param array $fields
	 * 
	 * @return array
	 */
	public function add_row_style_fields( array $fields ) : array {
		unset( $fields['row_stretch']);
		$fields['siw_row_stretch'] = [
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
	 * Voegt attributes voor rij toe
	 *
	 * @param array $style_attributes
	 * @param array $style_args
	 *
	 * @return void
	 */
	public function add_row_style_attributes( array $style_attributes, array $style_args ) : array {
		if ( ! isset( $style_args['siw_row_stretch'] ) ) {
			return $style_attributes;
		}

		if ( 'full_width_background' == $style_args['siw_row_stretch'] ) {
			$style_attributes['class'][] = 'row-full-width-background';
		}
		elseif ( 'full_width' == $style_args['siw_row_stretch'] ) {
			$style_attributes['class'][] = 'row-full-width';
		}
		return $style_attributes;
	}
}
