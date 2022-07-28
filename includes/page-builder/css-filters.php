<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Style_CSS as Style_CSS_Interface;
use SIW\Interfaces\Page_Builder\Style_Fields as Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Style_Group as Style_Group_Interface;

/**
 * Zichtbaarheidsopties voor Page Builder
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class CSS_Filters implements Style_Group_Interface, Style_Fields_Interface, Style_CSS_Interface {

	/** Style groep */
	const STYLE_GROUP = 'siw-css-filters';

	const STYLE_FIELD_BLUR = 'blur';
	const STYLE_FIELD_BRIGHTNESS = 'brightness';
	const STYLE_FIELD_CONTRAST = 'contrast';
	const STYLE_FIELD_GRAYSCALE = 'grayscale';
	const STYLE_FIELD_SEPIA = 'sepia';
	const STYLE_FIELD_SATURATE = 'saturate';


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
		if ( isset( $args['widget'] ) && \WP_Widget_Media_Image::class !== $args['widget'] ) {
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

		$fields[ self::STYLE_FIELD_BLUR ] = [
			'name'        => __( 'Blur', 'siw' ),
			'description' => __( 'px', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'number',
			'default'     => 0,
			'priority'    => 10,
		];

		$fields[ self::STYLE_FIELD_BRIGHTNESS ] = [
			'name'        => __( 'Helderheid', 'siw' ),
			'description' => __( '%', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'number',
			'default'     => 100,
			'priority'    => 20,
		];

		$fields[ self::STYLE_FIELD_CONTRAST ] = [
			'name'        => __( 'Contrast', 'siw' ),
			'description' => __( '%', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'number',
			'default'     => 100,
			'priority'    => 30,
		];

		$fields[ self::STYLE_FIELD_GRAYSCALE ] = [
			'name'        => __( 'Grayscale', 'siw' ),
			'description' => __( '%', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'number',
			'default'     => 0,
			'priority'    => 40,
		];
		$fields[ self::STYLE_FIELD_SEPIA ] = [
			'name'        => __( 'Sepia', 'siw' ),
			'description' => __( '%', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'number',
			'default'     => 0,
			'priority'    => 50,
		];

		$fields[ self::STYLE_FIELD_SATURATE ] = [
			'name'        => __( 'Verzadigen', 'siw' ),
			'description' => __( '%', 'siw' ),
			'group'       => self::STYLE_GROUP,
			'type'        => 'number',
			'default'     => 100,
			'priority'    => 60,
		];

		return $fields;
	}

	/** {@inheritDoc} */
	public function set_style_css( array $style_css, array $style_args ): array {

		$filter = [];

		if ( isset( $style_args[ self::STYLE_FIELD_BLUR ] ) && '0' !== $style_args[ self::STYLE_FIELD_BLUR ] ) {
			$filter[] = sprintf( 'blur(%spx)', $style_args[ self::STYLE_FIELD_BLUR ] );
		}

		if ( isset( $style_args[ self::STYLE_FIELD_BRIGHTNESS ] ) && '100' !== $style_args[ self::STYLE_FIELD_BRIGHTNESS ] ) {
			$filter[] = sprintf( 'brightness(%s%%)', $style_args[ self::STYLE_FIELD_BRIGHTNESS ] );
		}

		if ( isset( $style_args[ self::STYLE_FIELD_CONTRAST ] ) && '100' !== $style_args[ self::STYLE_FIELD_CONTRAST ] ) {
			$filter[] = sprintf( 'contrast(%s%%)', $style_args[ self::STYLE_FIELD_CONTRAST ] );
		}

		if ( isset( $style_args[ self::STYLE_FIELD_GRAYSCALE ] ) && '0' !== $style_args[ self::STYLE_FIELD_GRAYSCALE ] ) {
			$filter[] = sprintf( 'grayscale(%s%%)', $style_args[ self::STYLE_FIELD_GRAYSCALE ] );
		}

		if ( isset( $style_args[ self::STYLE_FIELD_SEPIA ] ) && '0' !== $style_args[ self::STYLE_FIELD_SEPIA ] ) {
			$filter[] = sprintf( 'sepia(%s%%)', $style_args[ self::STYLE_FIELD_SEPIA ] );
		}

		if ( isset( $style_args[ self::STYLE_FIELD_SATURATE ] ) && '100' !== $style_args[ self::STYLE_FIELD_SATURATE ] ) {
			$filter[] = sprintf( 'saturate(%s%%)', $style_args[ self::STYLE_FIELD_SATURATE ] );
		}

		if ( empty( $filter ) ) {
			return $style_css;
		}

		$style_css['filter'] = implode( ' ', $filter );
		$style_css['-webkit-filter'] = implode( ' ', $filter );

		return $style_css;
	}

}
