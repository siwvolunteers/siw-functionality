<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Style_Attributes as Style_Attributes_Interface;
use SIW\Interfaces\Page_Builder\Style_Fields as Style_Fields_Interface;

/**
 * Extra Design-opties voor Page Builder
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Design implements Style_Fields_Interface, Style_Attributes_Interface {

	/** Style field voor uitlijning widget title */
	const STYLE_FIELD_WIDGET_TITLE_ALIGN = 'siw_widget_title_align';

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
				''       => __( 'Standaard', 'siw' ),
				'left'   => __( 'Links', 'siw' ),
				'center' => __( 'Midden', 'siw' ),
				'right'  => __( 'Rechts', 'siw' ),
			],
		];
		return $fields;
	}

	/** {@inheritDoc} */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array {
		if ( ! isset( $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] ) || empty( $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] ) ) {
			return $style_attributes;
		}
		$style_attributes['class'][] = sprintf( 'widget-title-%s', $style_args[ self::STYLE_FIELD_WIDGET_TITLE_ALIGN ] );
		return $style_attributes;
	}
}
