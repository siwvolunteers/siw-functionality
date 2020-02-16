<?php

namespace SIW\Page_Builder;

use SIW\Animation as SIW_Animation;

/**
 * Animaties voor Page Builder
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Animation {

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
		add_filter( 'siteorigin_panels_row_style_groups', [ $self, 'add_style_group'] );
		add_filter( 'siteorigin_panels_widget_style_fields', [ $self, 'add_style_fields'] );
		add_filter( 'siteorigin_panels_row_style_fields', [ $self, 'add_style_fields'] );

		//Render attributes
		add_filter( 'siteorigin_panels_widget_style_attributes', [ $self, 'add_style_attributes'], 10, 2 );
		add_filter( 'siteorigin_panels_cell_style_attributes', [ $self, 'add_style_attributes'], 10, 2 );

		//Settings
		add_filter( 'siteorigin_panels_settings_fields', [ $self, 'add_settings' ], 100 );
		add_filter( 'siteorigin_panels_settings_defaults', [ $self, 'set_settings_defaults' ], 100 );
	}

	/**
	 * Voegt optiegroep voor animatie toe
	 *
	 * @param array $groups
	 * 
	 * @return array
	 */
	public function add_style_group( array $groups ) {
		$groups['siw-animation'] = [
			'name'     => __( 'Animatie', 'siw' ),
			'priority' => 110,
		];
		return $groups;
	}

	/**
	 * Voegt opties voor animaties toe
	 *
	 * @param array $fields
	 * 
	 * @return array
	 */
	public function add_style_fields( array $fields ) {
		$fields['siw_animation_type'] = [
			'name'        => __( 'Type', 'siw' ),
			'group'       => 'siw-animation',
			'type'        => 'select',
			'priority'    => 10,
			'options'     => [ 'none' => __( 'Geen', 'siw' ) ] + SIW_Animation::get_types(),
			'default'     => 'none',
		];
		$fields['siw_animation_duration'] = [
			'name'        => __( 'Duur', 'siw' ),
			'description' => sprintf( __( 'Standaard: %s', 'siw' ), SIW_Animation::get_duration_options()[ siteorigin_panels_setting( 'siw_animation_duration' ) ] ),
			'group'       => 'siw-animation',
			'type'        => 'select',
			'priority'    => 20,
			'options'     => [ 'default' => __( 'Standaard', 'siw' ) ] + SIW_Animation::get_duration_options(),
			'default'     => 'default',
		];
		$fields['siw_animation_delay'] = [
			'name'        => __( 'Vertraging', 'siw' ),
			'description' => sprintf( __( 'Standaard: %s', 'siw' ), SIW_Animation::get_delay_options()[ siteorigin_panels_setting( 'siw_animation_delay' ) ] ),
			'group'       => 'siw-animation',
			'type'        => 'select',
			'priority'    => 30,
			'options'     => [ 'default' => __( 'Standaard', 'siw' ) ] + SIW_Animation::get_delay_options(),
			'default'     => 'default',
		];
		$fields['siw_animation_easing'] = [
			'name'        => __( 'Easing', 'siw' ),
			'description' =>
				sprintf( __( 'Standaard: %s', 'siw' ), SIW_Animation::get_easing_options()[ siteorigin_panels_setting( 'siw_animation_easing' ) ] ) . BR .
				wp_targeted_link_rel( links_add_target( make_clickable( 'https://easings.net/' ) ) ),
			'group'       => 'siw-animation',
			'type'        => 'select',
			'priority'    => 40,
			'options'     => [ 'default' => __( 'Standaard', 'siw' ) ] + SIW_Animation::get_easing_options(),
			'default'     => 'default',
		];

		return $fields;
	}

	/**
	 * Voegt attributes toe voor animaties
	 *
	 * @param array $style_attributes
	 * @param array $style_args
	 * @return array
	 */
	public function add_style_attributes( array $style_attributes, array $style_args ) {

		//Afbreken als er geen animatie van toepassing is
		if ( ! isset( $style_args['siw_animation_type'] ) || 'none' === $style_args['siw_animation_type'] ) {
			return $style_attributes;
		}

		//Type animatie
		$style_attributes['data-sal'] = $style_args['siw_animation_type'];

		//Duur van animatie
		if ( ! isset( $style_args['siw_animation_duration'] ) || 'default' === $style_args['siw_animation_duration'] ) {
			$style_attributes['data-sal-duration'] = siteorigin_panels_setting( 'siw_animation_duration' );
		}
		else {
			$style_attributes['data-sal-duration'] = $style_args['siw_animation_duration'];
		}

		//Vertraging van animatie
		if ( ! isset( $style_args['siw_animation_delay'] ) || 'default' === $style_args['siw_animation_delay'] ) {
			$style_attributes['data-sal-delay'] = siteorigin_panels_setting( 'siw_animation_delay' );
		}
		else {
			$style_attributes['data-sal-delay'] = $style_args['siw_animation_delay'];
		}

		//Easing van animatie
		if ( ! isset( $style_args['siw_animation_easing'] ) || 'default' === $style_args['siw_animation_easing'] ) {
			$style_attributes['data-sal-easing'] = siteorigin_panels_setting( 'siw_animation_easing' );
		}
		else {
			$style_attributes['data-sal-easing'] = $style_args['siw_animation_easing'];
		}

		return $style_attributes;
	}

	/**
	 * Voegt instelling voor animaties to aan PB-settings
	 *
	 * @param array $fields
	 * 
	 * @return array
	 */
	public function add_settings( array $fields ) {
		$fields['siw-animation'] = [
			'title'  => __( 'Animatie', 'siw' ),
			'fields' => [
				'siw_animation_duration' => [
					'label'       => __( 'Duur', 'siw'),
					'description' => __( 'Standaard duur van de animatie', 'siw' ),
					'type'        => 'select',
					'options'     => SIW_Animation::get_duration_options(),
				],
				'siw_animation_delay' => [
					'label'       => __( 'Vertraging', 'siw'),
					'description' => __( 'Standaard vertraging van de animatie', 'siw' ),
					'type'        => 'select',
					'options'     => SIW_Animation::get_delay_options(),
				],
				'siw_animation_easing' => [
					'label'       => __( 'Easing', 'siw'),
					'description' =>
						__( 'Standaard easing van de animatie', 'siw' ),
						wp_targeted_link_rel( links_add_target( make_clickable( 'https://easings.net/' ) ) ),
					'type'        => 'select',
					'options'     => SIW_Animation::get_easing_options(),
				]
			],
		];
		return $fields;
	}

	/**
	 * Zet standaardwaarden voor PB-settings
	 *
	 * @param array $defaults
	 * 
	 * @return array
	 */
	public function set_settings_defaults( array $defaults)  {
		$defaults['siw_animation_duration'] = '1000';
		$defaults['siw_animation_delay']    = 'none';
		$defaults['siw_animation_easing']   = 'ease-out-sine';
		return $defaults;
	}

}
