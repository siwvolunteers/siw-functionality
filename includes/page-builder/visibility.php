<?php

namespace SIW\Page_Builder;

/**
 * Zichtbaarheidsopties voor Page Builder
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Visibility {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! class_exists( '\SiteOrigin_Panels' ) ) {
			return;
		}
		$self = new self();
		
		//Editor settings
		add_filter( 'siteorigin_panels_widget_style_groups', [ $self, 'add_style_group'] );
		add_filter( 'siteorigin_panels_cell_style_groups', [ $self, 'add_style_group'] );
		add_filter( 'siteorigin_panels_row_style_groups', [ $self, 'add_style_group'] );
		add_filter( 'siteorigin_panels_widget_style_fields', [ $self, 'add_style_fields'] );
		add_filter( 'siteorigin_panels_cell_style_fields', [ $self, 'add_style_fields'] );
		add_filter( 'siteorigin_panels_row_style_fields', [ $self, 'add_style_fields'] );
		
		//Render attributes
		add_filter( 'siteorigin_panels_widget_style_attributes', [ $self, 'add_style_attributes'], 10, 2 );
		add_filter( 'siteorigin_panels_cell_style_attributes', [ $self, 'add_style_attributes'], 10, 2 );
		add_filter( 'siteorigin_panels_row_style_attributes', [ $self, 'add_style_attributes'], 10, 2 );
	}

	/**
	 * Voegt optiegroep voor zichtbaarheid toe
	 *
	 * @param array $groups
	 * @return array
	 */
	public function add_style_group( array $groups ) : array {
		$groups['siw-visibility'] = [
			'name'     => __( 'Zichtbaarheid', 'siw' ),
			'priority' => 99,
		];
		return $groups;
	}

	/**
	 * Voegt opties voor zichtbaarheid toe
	 *
	 * @param array $fields
	 * @return array
	 */
	public function add_style_fields( array $fields ) : array {
		$fields['hide_on_mobile'] = [
			'name'     => '<span class="dashicons dashicons-smartphone"></span>' . __( 'Mobiel', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => 'siw-visibility',
			'type'     => 'checkbox',
			'priority' => 10,
		];
		$fields['hide_on_tablet'] = [
			'name'     => '<span class="dashicons dashicons-tablet"></span>' . __( 'Tablet', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => 'siw-visibility',
			'type'     => 'checkbox',
			'priority' => 20,
		];
		$fields['hide_on_desktop'] = [
			'name'     => '<span class="dashicons dashicons-desktop"></span>' . __( 'Desktop', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => 'siw-visibility',
			'type'     => 'checkbox',
			'priority' => 30,
		];
		return $fields;
	}

	/**
	 * Voegt css-klasses toe voor zichtbaarheid
	 *
	 * @param array $style_attributes
	 * @param array $style_args
	 * 
	 * @return array
	 */
	public function add_style_attributes( array $style_attributes, array $style_args ) : array {
		if ( isset( $style_args['hide_on_mobile'] ) && 1 == $style_args['hide_on_mobile'] ) {
			$style_attributes['class'][] = 'hide-on-mobile';
		}
		if ( isset( $style_args['hide_on_tablet'] ) && 1 == $style_args['hide_on_tablet'] ) {
			$style_attributes['class'][] = 'hide-on-tablet';
		}
		if ( isset( $style_args['hide_on_desktop'] ) && 1 == $style_args['hide_on_desktop'] ) {
			$style_attributes['class'][] = 'hide-on-desktop';
		}
		return $style_attributes;
	}
}
