<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Style_Attributes as I_Style_Attributes;
use SIW\Interfaces\Page_Builder\Style_Fields as I_Style_Fields;

class Design implements I_Style_Fields, I_Style_Attributes {

	private const STYLE_FIELD_WIDGET_TITLE_ALIGN = 'siw_widget_title_align';
	private const STYLE_FIELD_TEXT_ALIGN = 'siw_widget_text_align';

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
	public function add_style_fields( array $fields, int|bool $post_id, array|bool $args ): array {
		$fields[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] = [
			'name'     => __( 'Uitlijning widget titel', 'siw' ),
			'type'     => 'select',
			'group'    => 'design',
			'priority' => 1,
			'options'  => [
				''        => __( 'Standaard', 'siw' ),
				'left'    => __( 'Links', 'siw' ),
				'center'  => __( 'Midden', 'siw' ),
				'right'   => __( 'Rechts', 'siw' ),
				'justify' => __( 'Uitvullen', 'siw' ),
			],
		];
		$fields[ self::STYLE_FIELD_TEXT_ALIGN ] = [
			'name'     => __( 'Uitlijning', 'siw' ),
			'type'     => 'select',
			'group'    => 'design',
			'priority' => 2,
			'options'  => [
				''        => __( 'Standaard', 'siw' ),
				'left'    => __( 'Links', 'siw' ),
				'center'  => __( 'Midden', 'siw' ),
				'right'   => __( 'Rechts', 'siw' ),
				'justify' => __( 'Uitvullen', 'siw' ),
			],
		];
		return $fields;
	}

	/** {@inheritDoc} */
	public function set_style_attributes( array $style_attributes, array $style_args ): array {
		if ( isset( $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] ) && ! empty( $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] ) ) {
			$style_attributes['class'][] = sprintf( 'widget-title-align-%s', $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] );
			return $style_attributes;
		}

		if ( isset( $style_args[ self::STYLE_FIELD_TEXT_ALIGN ] ) && ! empty( $style_args[ self::STYLE_FIELD_TEXT_ALIGN ] ) ) {
			$style_attributes['class'][] = sprintf( 'align-%s', $style_args[ self::STYLE_FIELD_TEXT_ALIGN ] );
			return $style_attributes;
		}

		return $style_attributes;
	}
}
