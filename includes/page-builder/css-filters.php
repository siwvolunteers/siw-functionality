<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Style_CSS as I_Style_CSS;
use SIW\Interfaces\Page_Builder\Style_Fields as I_Style_Fields;
use SIW\Interfaces\Page_Builder\Style_Group as I_Style_Group;

/**
 * Zichtbaarheidsopties voor Page Builder
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class CSS_Filters implements I_Style_Group, I_Style_Fields, I_Style_CSS {

	private const SUPPORTED_WIDGETS = [
		\WP_Widget_Media_Image::class,
		\SIW\Widgets\Featured_Image::class,
	];

	/** Style groep */
	private const STYLE_GROUP = 'siw_css_filters';

	private const STYLE_FIELD_BLUR = 'blur';
	private const STYLE_FIELD_BRIGHTNESS = 'brightness';
	private const STYLE_FIELD_CONTRAST = 'contrast';
	private const STYLE_FIELD_GRAYSCALE = 'grayscale';
	private const STYLE_FIELD_SEPIA = 'sepia';
	private const STYLE_FIELD_SATURATE = 'saturate';


	/** {@inheritDoc} */
	public function supports_widgets(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function supports_cells(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function supports_rows(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function add_style_group( array $groups, int|bool $post_id, array|bool $args ): array {
		if ( isset( $args['widget'] ) && ! in_array( $args['widget'], self::SUPPORTED_WIDGETS, true ) ) {
			return $groups;
		}

		$groups[ self::STYLE_GROUP ] = [
			'name'     => __( 'CSS filters', 'siw' ),
			'priority' => 90,
		];
		return $groups;
	}

	/** {@inheritDoc} */
	public function add_style_fields( array $fields, int|bool $post_id, array|bool $args ): array {

		if ( isset( $args['widget'] ) && \WP_Widget_Media_Image::class !== $args['widget'] ) {
			return $fields;
		}

		$fields[ self::STYLE_GROUP ] = [
			'name'     => __( 'CSS filters', 'siw' ),
			'type'     => 'toggle',
			'group'    => self::STYLE_GROUP,
			'priority' => 10,
			'fields'   => [
				self::STYLE_FIELD_BLUR       => [
					'name'        => __( 'Blur', 'siw' ),
					'description' => 'px',
					'type'        => 'number',
					'default'     => 0,
					'priority'    => 10,
				],
				self::STYLE_FIELD_BRIGHTNESS => [
					'name'        => __( 'Helderheid', 'siw' ),
					'description' => '%',
					'type'        => 'number',
					'default'     => 100,
					'priority'    => 20,
				],
				self::STYLE_FIELD_CONTRAST   => [
					'name'        => __( 'Contrast', 'siw' ),
					'description' => '%',
					'type'        => 'number',
					'default'     => 100,
					'priority'    => 30,
				],
				self::STYLE_FIELD_GRAYSCALE  => [
					'name'        => __( 'Grayscale', 'siw' ),
					'description' => '%',
					'type'        => 'number',
					'default'     => 0,
					'priority'    => 40,
				],
				self::STYLE_FIELD_SEPIA      => [
					'name'        => __( 'Sepia', 'siw' ),
					'description' => '%',
					'type'        => 'number',
					'default'     => 0,
					'priority'    => 50,
				],
				self::STYLE_FIELD_SATURATE   => [
					'name'        => __( 'Verzadigen', 'siw' ),
					'description' => '%',
					'type'        => 'number',
					'default'     => 100,
					'priority'    => 60,
				],
			],
		];
		return $fields;
	}

	/** {@inheritDoc} */
	public function set_style_css( array $style_css, array $style_args ): array {

		if ( empty( $style_args[ self::STYLE_GROUP ] ) ) {
			return $style_css;
		}

		$css_filter = [
			$this->generate_filter( $style_args, self::STYLE_FIELD_BLUR, self::STYLE_GROUP, '0', 'px' ),
			$this->generate_filter( $style_args, self::STYLE_FIELD_BRIGHTNESS, self::STYLE_GROUP, '100', '%' ),
			$this->generate_filter( $style_args, self::STYLE_FIELD_CONTRAST, self::STYLE_GROUP, '100', '%' ),
			$this->generate_filter( $style_args, self::STYLE_FIELD_GRAYSCALE, self::STYLE_GROUP, '0', '%' ),
			$this->generate_filter( $style_args, self::STYLE_FIELD_SEPIA, self::STYLE_GROUP, '0', '%' ),
			$this->generate_filter( $style_args, self::STYLE_FIELD_SATURATE, self::STYLE_GROUP, '100', '%' ),
		];

		array_filter( $css_filter );

		if ( empty( $css_filter ) ) {
			return $style_css;
		}

		$style_css['filter'] = implode( ' ', $css_filter );
		$style_css['-webkit-filter'] = implode( ' ', $css_filter );

		return $style_css;
	}

	/** Genereert filter  */
	protected function generate_filter( array $style_args, string $field, string $prefix, string $default_value, string $unit ): ?string {
		if ( ! isset( $style_args[ "{$prefix}_{$field}" ] ) || $default_value === $style_args[ "{$prefix}_{$field}" ] ) {
			return null;
		}
		$value = $style_args[ "{$prefix}_{$field}" ];
		return "{$field}({$value}{$unit})";
	}
}
