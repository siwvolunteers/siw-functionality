<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor SiteOrigin Page Builder
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Compat_SiteOrigin_Page_Builder {

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( 'SiteOrigin_Panels' ) ) {
			return;
		}
		$self = new self();
		add_action( 'admin_init', [ $self, 'remove_dashboard_widget' ] );
		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], 99 );
		add_filter( 'siteorigin_panels_widget_style_groups', [ $self, 'add_visibility_style_group'] );
		add_filter( 'siteorigin_panels_row_style_groups', [ $self, 'add_visibility_style_group'] );
		add_filter( 'siteorigin_panels_widget_style_fields', [ $self, 'add_visibility_style_fields'] );
		add_filter( 'siteorigin_panels_row_style_fields', [ $self, 'add_visibility_style_fields'] );
		add_filter( 'siteorigin_panels_widget_style_attributes', [ $self, 'add_visibility_style_attributes'], 10, 2 );
		add_filter( 'siteorigin_panels_cell_style_attributes', [ $self, 'add_visibility_style_attributes'], 10, 2 );
		add_filter( 'siteorigin_panels_widget_dialog_tabs', [ $self, 'add_widget_tab'] );
	}

	/**
	 * Verwijdert dashboard widget
	 */
	public function remove_dashboard_widget() {
		remove_meta_box( 'so-dashboard-news', 'dashboard', 'normal' );
	}

	/**
	 * Verwijdert Page Builder widgets
	 */
	public function unregister_widgets() {
		unregister_widget( 'SiteOrigin_Panels_Widgets_PostContent' );
		unregister_widget( 'SiteOrigin_Panels_Widgets_PostLoop' );
		unregister_widget( 'SiteOrigin_Panels_Widgets_Layout' );
		unregister_widget( 'SiteOrigin_Panels_Widgets_Gallery' );
	}

	/**
	 * Voegt optiegroep voor zichtbaarheid toe
	 *
	 * @param array $groups
	 * @return array
	 */
	public function add_visibility_style_group( $groups ) {
		$groups['visibility'] = [
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
	public function add_visibility_style_fields( $fields ) {
		$fields['hide_on_mobile'] = [
			'name'     => '<span class="dashicons dashicons-smartphone"></span>' . __( 'Mobiel', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => 'visibility',
			'type'     => 'checkbox',
			'priority' => 10,
		];
		$fields['hide_on_desktop'] = [
			'name'     => '<span class="dashicons dashicons-desktop"></span>' . __( 'Desktop', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => 'visibility',
			'type'     => 'checkbox',
			'priority' => 20,
		];
		return $fields;
	}

	/**
	 * Voegt css-klasses toe voor zichtbaarheid
	 *
	 * @param array $style_attributes
	 * @param array $style_args
	 * @return array
	 */
	public function add_visibility_style_attributes( $style_attributes, $style_args ) {
		if ( isset( $style_args['hide_on_mobile'] ) && 1 == $style_args['hide_on_mobile'] ) {
			$style_attributes['class'][] = 'hidden-xs';
		}
		if ( isset( $style_args['hide_on_desktop'] ) && 1 == $style_args['hide_on_desktop'] ) {
			$style_attributes['class'][] = 'hidden-sm';
			$style_attributes['class'][] = 'hidden-md';
			$style_attributes['class'][] = 'hidden-lg';
		}
		return $style_attributes;
	}

	/**
	 * Voegt tab voor SIW-widgets toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_widget_tab( $tabs ) {
		$tabs[] = [
			'title'  => __( 'SIW Widgets', 'siw' ),
			'filter' => [
				'groups' => [ 'siw' ],
			],
		];
		return $tabs;
	}
}