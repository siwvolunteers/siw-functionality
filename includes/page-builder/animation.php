<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Animation as SIW_Animation;

use SIW\Interfaces\Page_Builder\Row_Style_Group as Row_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Cell_Style_Group as Cell_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Widget_Style_Group as Widget_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Settings as Settings_Interface;

/**
 * Animaties voor Page Builder
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Animation implements Row_Style_Group_Interface, Cell_Style_Group_Interface, Widget_Style_Group_Interface, Settings_Interface {

	/** Style group */
	const STYLE_GROUP = 'siw_animation';

	/** Style field voor animatie type */
	const STYLE_FIELD_TYPE = 'siw_animation_type';

	/** Style field voor animatie duur */
	const STYLE_FIELD_DURATION = 'siw_animation_duration';

	/** Style field voor animatie vertraging */
	const STYLE_FIELD_DELAY = 'siw_animation_delay';

	/** Style field voor animatie easing */
	const STYLE_FIELD_EASING = 'siw_animation_easing';

	/** Style field voor animatie herhalen */
	const STYLE_FIELD_REPEAT = 'siw_animation_repeat';

	/** Option group */
	const OPTION_GROUP = 'siw_animation';

	/** Option field voor animatie duur */
	const OPTION_FIELD_DURATION = 'siw_animation_duration';

	/** Option field voor animatie vertraging */
	const OPTION_FIELD_DELAY = 'siw_animation_delay';
	
	/** Option field voor animatie easing */
	const OPTION_FIELD_EASING = 'siw_animation_easing';

	/**
	 * {@inheritDoc}
	 */
	public function add_style_group( array $groups ) : array {
		$groups[ self::STYLE_GROUP ] = [
			'name'     => __( 'Animatie', 'siw' ),
			'priority' => 110,
		];
		return $groups;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_style_fields( array $fields ) : array {
		$fields[ self::STYLE_FIELD_TYPE ] = [
			'name'        => __( 'Type', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'select',
			'priority'    => 10,
			'options'     => $this->get_types(),
			'default'     => 'none',
		];
		$fields[ self::STYLE_FIELD_DURATION ] = [
			'name'        => __( 'Duur', 'siw' ),
			'description' => sprintf( __( 'Standaard: %s', 'siw' ), $this->get_default_duration() ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'select',
			'priority'    => 20,
			'options'     => $this->get_duration_options(),
			'default'     => 'default',
		];
		$fields[ self::STYLE_FIELD_DELAY ] = [
			'name'        => __( 'Vertraging', 'siw' ),
			'description' => sprintf( __( 'Standaard: %s', 'siw' ), $this->get_default_delay() ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'select',
			'priority'    => 30,
			'options'     => $this->get_delay_options(),
			'default'     => 'default',
		];
		$fields[ self::STYLE_FIELD_EASING ] = [
			'name'        => __( 'Easing', 'siw' ),
			'description' =>
				sprintf( __( 'Standaard: %s', 'siw' ), $this->get_default_easing() ) . BR .
				wp_targeted_link_rel( links_add_target( make_clickable( 'https://easings.net/' ) ) ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'select',
			'priority'    => 40,
			'options'     => $this->get_easing_options(),
			'default'     => 'default',
		];
		$fields[ self::STYLE_FIELD_REPEAT ] = [
			'name'        => __( 'Herhalen', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'checkbox',
			'priority'    => 50,
		];
		return $fields;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array {

		//Afbreken als er geen animatie van toepassing is
		if ( ! isset( $style_args[ self::STYLE_FIELD_TYPE ] ) || 'none' === $style_args[ self::STYLE_FIELD_TYPE ] ) {
			return $style_attributes;
		}

		//Type animatie
		$style_attributes['data-sal'] = $style_args[ self::STYLE_FIELD_TYPE ];

		//Duur van animatie
		if ( ! isset( $style_args[ self::STYLE_FIELD_DURATION ] ) || 'default' === $style_args[ self::STYLE_FIELD_DURATION ] ) {
			$style_attributes['data-sal-duration'] = siteorigin_panels_setting( self::OPTION_FIELD_DURATION );
		}
		else {
			$style_attributes['data-sal-duration'] = $style_args[ self::STYLE_FIELD_DURATION ];
		}

		//Vertraging van animatie
		if ( ! isset( $style_args[ self::STYLE_FIELD_DELAY] ) || 'default' === $style_args[ self::STYLE_FIELD_DELAY ] ) {
			$style_attributes['data-sal-delay'] = siteorigin_panels_setting( 'siw_animation_delay' );
		}
		else {
			$style_attributes['data-sal-delay'] = $style_args[ self::STYLE_FIELD_DELAY ];
		}

		//Easing van animatie
		if ( ! isset( $style_args[ self::STYLE_FIELD_EASING ] ) || 'default' === $style_args[ self::STYLE_FIELD_EASING ] ) {
			$style_attributes['data-sal-easing'] = siteorigin_panels_setting( 'siw_animation_easing' );
		}
		else {
			$style_attributes['data-sal-easing'] = $style_args[ self::STYLE_FIELD_EASING ];
		}

		//Herhalen
		if ( isset( $style_args[ self::STYLE_FIELD_REPEAT ] ) && $style_args[ self::STYLE_FIELD_REPEAT ] ) {
			$style_attributes['data-sal-repeat'] = true;
		}

		return $style_attributes;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_settings( array $fields ) : array {
		$fields[ self::OPTION_GROUP ] = [
			'title'  => __( 'Animatie', 'siw' ),
			'fields' => [
				self::OPTION_FIELD_DURATION => [
					'label'       => __( 'Duur', 'siw' ),
					'description' => __( 'Standaard duur van de animatie', 'siw' ),
					'type'        => 'select',
					'options'     => $this->get_duration_options( false )
				],
				self::OPTION_FIELD_DELAY => [
					'label'       => __( 'Vertraging', 'siw' ),
					'description' => __( 'Standaard vertraging van de animatie', 'siw' ),
					'type'        => 'select',
					'options'     => $this->get_delay_options( false ),
				],
				self::OPTION_FIELD_EASING => [
					'label'       => __( 'Easing', 'siw' ),
					'description' =>
						__( 'Standaard easing van de animatie', 'siw' ),
						wp_targeted_link_rel( links_add_target( make_clickable( 'https://easings.net/' ) ) ),
					'type'        => 'select',
					'options'     => $this->get_easing_options( false )
				]
			],
		];
		return $fields;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_settings_defaults( array $defaults) : array {
		$defaults[ self::OPTION_FIELD_DURATION ] = '1000';
		$defaults[ self::OPTION_FIELD_DELAY ]    = 'none';
		$defaults[ self::OPTION_FIELD_EASING ]   = 'ease-out-sine';
		return $defaults;
	}

	/** Geeft animatie type terug */
	protected function get_types() : array {
		return [ 'none' => __( 'Geen', 'siw' ) ] + SIW_Animation::get_types();
	}

	/** Geeft easing opties terug */
	protected function get_easing_options( bool $include_default_option = true ) : array {
		return $this->maybe_add_default_option( SIW_Animation::get_easing_options(), $include_default_option );
	}

	/** Geeft vertraging opties terug */
	protected function get_delay_options( bool $include_default_option = true ) : array {
		return $this->maybe_add_default_option( SIW_Animation::get_delay_options(), $include_default_option );
	}

	/** Geeft duur opties terug */
	protected function get_duration_options( bool $include_default_option = true ) : array {
		return $this->maybe_add_default_option( SIW_Animation::get_duration_options(), $include_default_option );
	}

	/** Voegt eventueel default optie toe */
	protected function maybe_add_default_option( array $options, bool $add_default_option ) {
		if ( $add_default_option ) {
			$options = [ 'default' => __( 'Standaard', 'siw' ) ] + $options;
		}
		return $options;
	}

	/** Geeft standaard easing terug */
	protected function get_default_easing() : string {
		$easing_options = $this->get_easing_options();
		return $easing_options[ siteorigin_panels_setting( self::OPTION_FIELD_EASING ) ] ?? '';
	}

	/** Geeft standaard duur terug */
	protected function get_default_duration() : string {
		$duration_options = $this->get_duration_options();
		return $duration_options[ siteorigin_panels_setting( self::OPTION_FIELD_DURATION ) ] ?? '';
	}

	/** Geeft standaard vertraging terug */
	protected function get_default_delay() : string {
		$delay_options = $this->get_delay_options();
		return $delay_options[ siteorigin_panels_setting( self::OPTION_FIELD_DELAY ) ] ?? '';
	}
}
