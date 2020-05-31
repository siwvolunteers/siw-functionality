<?php

namespace SIW\Page_Builder;

/**
 * Design-opties voor Page Builder
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Design {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! class_exists( '\SiteOrigin_Panels' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'siteorigin_panels_widget_style_fields', [ $self, 'add_widget_style_fields'] );
		add_filter( 'siteorigin_panels_widget_style_attributes', [ $self, 'add_widget_style_attributes'], 10, 2 );
	}

	/**
	 * Voegt opties voor widget toe
	 *
	 * @param array $fields
	 * 
	 * @return array
	 */
	public function add_widget_style_fields( array $fields ) : array {
		$fields['siw_widget_title_align'] = [
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
	 * Voegt attributes voor widget toe
	 *
	 * @param array $style_attributes
	 * @param array $style_args
	 *
	 * @return array
	 */
	public function add_widget_style_attributes( array $style_attributes, array $style_args ) : array {
		if ( ! isset( $style_args['siw_widget_title_align'] ) || '' == $style_args['siw_widget_title_align'] ) {
			return $style_attributes;
		}
		$style_attributes['class'][] = "widget-title-{$style_args['siw_widget_title_align']}";
		return $style_attributes;
	}
}
