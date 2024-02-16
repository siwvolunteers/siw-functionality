<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Animation\Easing;
use SIW\Data\Animation\Type;
use SIW\Facades\SiteOrigin;

class Animation extends Base {

	private const STYLE_GROUP = 'siw_animation';
	private const STYLE_FIELD_TYPE = 'type';
	private const STYLE_FIELD_DURATION = 'duration';
	private const STYLE_FIELD_DELAY = 'delay';
	private const STYLE_FIELD_EASING = 'easing';
	private const STYLE_FIELD_REPEAT = 'repeat';
	private const OPTION_GROUP = 'siw_animation';
	private const OPTION_FIELD_DURATION = 'siw_animation_duration';
	private const OPTION_FIELD_DELAY = 'siw_animation_delay';
	private const OPTION_FIELD_EASING = 'siw_animation_easing';

	#[Add_Filter( 'siteorigin_panels_row_style_groups' )]
	#[Add_Filter( 'siteorigin_panels_cell_style_groups' )]
	#[Add_Filter( 'siteorigin_panels_widget_style_groups' )]
	public function add_style_group( array $groups, int|bool $post_id, array|bool $args ): array {
		$groups[ self::STYLE_GROUP ] = [
			'name'     => __( 'Animatie', 'siw' ),
			'priority' => 110,
		];
		return $groups;
	}

	#[Add_Filter( 'siteorigin_panels_row_style_fields' )]
	#[Add_Filter( 'siteorigin_panels_cell_style_fields' )]
	#[Add_Filter( 'siteorigin_panels_widget_style_fields' )]
	public function add_style_fields( array $fields, int|bool $post_id, array|bool $args ): array {

		$fields[ self::STYLE_GROUP ] = [
			'name'     => __( 'Animatie', 'siw' ),
			'type'     => 'toggle',
			'group'    => self::STYLE_GROUP,
			'priority' => 10,
			'fields'   => [
				self::STYLE_FIELD_TYPE     => [
					'name'     => __( 'Type', 'siw' ),
					'group'    => self::STYLE_GROUP,
					'type'     => 'select',
					'priority' => 10,
					'options'  => Type::list(),
				],
				self::STYLE_FIELD_DURATION => [
					'name'        => __( 'Duur', 'siw' ),
					// translators: %s is de standaard instelling
					'description' => sprintf( __( 'Standaard: %s', 'siw' ), $this->get_default_duration() ),
					'type'        => 'select',
					'priority'    => 20,
					'options'     => $this->get_duration_options(),
					'default'     => 'default',
				],
				self::STYLE_FIELD_DELAY    => [
					'name'        => __( 'Vertraging', 'siw' ),
					// translators: %s is de standaard instelling
					'description' => sprintf( __( 'Standaard: %s', 'siw' ), $this->get_default_delay() ),
					'type'        => 'select',
					'priority'    => 30,
					'options'     => $this->get_delay_options(),
					'default'     => 'default',
				],
				self::STYLE_FIELD_EASING   => [
					'name'        => __( 'Easing', 'siw' ),
					'description' =>
					// translators: %s is de standaard instelling
					sprintf( __( 'Standaard: %s', 'siw' ), $this->get_default_easing() ) . '<br>' .
					wp_targeted_link_rel( links_add_target( make_clickable( 'https://easings.net/' ) ) ),
					'type'        => 'select',
					'priority'    => 40,
					'options'     => $this->get_easing_options(),
					'default'     => 'default',
				],
				self::STYLE_FIELD_REPEAT   => [
					'name'     => __( 'Herhalen', 'siw' ),
					'type'     => 'checkbox',
					'priority' => 50,
				],
			],
		];
		return $fields;
	}

	#[Add_Filter( 'siteorigin_panels_row_style_attributes' )]
	#[Add_Filter( 'siteorigin_panels_cell_style_attributes' )]
	#[Add_Filter( 'siteorigin_panels_widget_style_attributes' )]
	public function set_style_attributes( array $style_attributes, array $style_args ): array {

		// Afbreken als er geen animatie van toepassing is
		if ( empty( $style_args[ self::STYLE_GROUP ] ) ) {
			return $style_attributes;
		}

		// Type animatie
		$style_attributes['data-sal'] = $style_args[ self::STYLE_GROUP . '_' . self::STYLE_FIELD_TYPE ];
		$style_attributes['data-sal-duration'] = $this->get_attribute_value( $style_args, self::STYLE_FIELD_DURATION, self::STYLE_GROUP, self::OPTION_FIELD_DURATION );
		$style_attributes['data-sal-delay'] = $this->get_attribute_value( $style_args, self::STYLE_FIELD_DELAY, self::STYLE_GROUP, self::OPTION_FIELD_DELAY );
		$style_attributes['data-sal-easing'] = $this->get_attribute_value( $style_args, self::STYLE_FIELD_EASING, self::STYLE_GROUP, self::OPTION_FIELD_EASING );
		if ( isset( $style_args[ self::STYLE_FIELD_REPEAT ] ) && $style_args[ self::STYLE_FIELD_REPEAT ] ) {
			$style_attributes['data-sal-repeat'] = true;
		} else {
			$style_attributes['data-sal-once'] = true;
		}

		return $style_attributes;
	}

	protected function get_attribute_value( array $style_args, string $field, string $prefix, string $default_option ): ?string {
		if ( ! isset( $style_args[ "{$prefix}_{$field}" ] ) || 'default' === $style_args[ "{$prefix}_{$field}" ] ) {
			return SiteOrigin::panels_setting( $default_option );
		}
		return $style_args[ "{$prefix}_{$field}" ];
	}

	#[Add_Filter( 'siteorigin_panels_settings_fields' )]
	public function add_settings( array $fields ): array {
		$fields[ self::OPTION_GROUP ] = [
			'title'  => __( 'Animatie', 'siw' ),
			'fields' => [
				self::OPTION_FIELD_DURATION => [
					'label'       => __( 'Duur', 'siw' ),
					'description' => __( 'Standaard duur van de animatie', 'siw' ),
					'type'        => 'select',
					'options'     => $this->get_duration_options( false ),
				],
				self::OPTION_FIELD_DELAY    => [
					'label'       => __( 'Vertraging', 'siw' ),
					'description' => __( 'Standaard vertraging van de animatie', 'siw' ),
					'type'        => 'select',
					'options'     => $this->get_delay_options( false ),
				],
				self::OPTION_FIELD_EASING   => [
					'label'       => __( 'Easing', 'siw' ),
					'description' =>
						__( 'Standaard easing van de animatie', 'siw' ),
					wp_targeted_link_rel( links_add_target( make_clickable( 'https://easings.net/' ) ) ),
					'type'        => 'select',
					'options'     => $this->get_easing_options( false ),
				],
			],
		];
		return $fields;
	}

	#[Add_Filter( 'siteorigin_panels_settings_defaults' )]
	public function set_settings_defaults( array $defaults ): array {
		$defaults[ self::OPTION_FIELD_DURATION ] = '1000';
		$defaults[ self::OPTION_FIELD_DELAY ]    = 'none';
		$defaults[ self::OPTION_FIELD_EASING ]   = Easing::EASE_IN_OUT_SINE->value;
		return $defaults;
	}

	protected function get_easing_options( bool $include_default_option = true ): array {
		return $this->maybe_add_default_option( Easing::list(), $include_default_option );
	}

	protected function get_delay_options( bool $include_default_option = true ): array {

		$delay_options['none'] = __( 'Geen', 'siw' );
		for ( $t = 100; $t <= 1000; $t += 50 ) {
			// translators: %d is het aantal miliseconden
			$delay_options[ $t ] = sprintf( __( '%d ms', 'siw' ), $t );
		}
		return $this->maybe_add_default_option( $delay_options, $include_default_option );
	}

	protected function get_duration_options( bool $include_default_option = true ): array {
		$duration_options = [];
		for ( $t = 200; $t <= 2000; $t += 50 ) {
			// translators: %d is het aantal miliseconden
			$duration_options[ $t ] = sprintf( __( '%d ms', 'siw' ), $t );
		}

		return $this->maybe_add_default_option( $duration_options, $include_default_option );
	}

	protected function maybe_add_default_option( array $options, bool $add_default_option ) {
		if ( $add_default_option ) {
			$options = [ 'default' => __( 'Standaard', 'siw' ) ] + $options;
		}
		return $options;
	}

	protected function get_default_easing(): string {
		$easing_options = $this->get_easing_options();
		return $easing_options[ SiteOrigin::panels_setting( self::OPTION_FIELD_EASING ) ] ?? '';
	}

	protected function get_default_duration(): string {
		$duration_options = $this->get_duration_options();
		return $duration_options[ SiteOrigin::panels_setting( self::OPTION_FIELD_DURATION ) ] ?? '';
	}

	protected function get_default_delay(): string {
		$delay_options = $this->get_delay_options();
		return $delay_options[ SiteOrigin::panels_setting( self::OPTION_FIELD_DELAY ) ] ?? '';
	}
}
