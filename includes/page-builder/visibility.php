<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Style_Attributes as I_Style_Attributes;
use SIW\Interfaces\Page_Builder\Style_Fields as I_Style_Fields;
use SIW\Interfaces\Page_Builder\Style_Group as I_Style_Group;

use SIW\Util\CSS;

/**
 * Zichtbaarheidsopties voor Page Builder
 *
 * @copyright 2021-2022 SIW Internationale Vrijwilligersprojecten
 */
class Visibility implements I_Style_Group, I_Style_Fields, I_Style_Attributes {

	/** Style groep */
	private const STYLE_GROUP = 'siw_visibility';

	/** Style fields voor verbergen op mobiel */
	private const STYLE_FIELD_HIDE_ON_MOBILE = 'hide_on_mobile';

	/** Style fields voor verbergen op tablet */
	private const STYLE_FIELD_HIDE_ON_TABLET = 'hide_on_tablet';

	/** Style fields voor verbergen op desktop */
	private const STYLE_FIELD_HIDE_ON_DESKTOP = 'hide_on_desktop';

	/** {@inheritDoc} */
	public function supports_widgets(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function supports_cells(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function supports_rows(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function add_style_group( array $groups, int|bool $post_id, array|bool $args ): array {
		$groups[ self::STYLE_GROUP ] = [
			'name'     => __( 'Zichtbaarheid', 'siw' ),
			'priority' => 99,
		];
		return $groups;
	}

	/** {@inheritDoc} */
	public function add_style_fields( array $fields, int|bool $post_id, array|bool $args ): array {
		$fields[ self::STYLE_FIELD_HIDE_ON_MOBILE ] = [
			'name'     => '<span class="dashicons dashicons-smartphone"></span>' . __( 'Mobiel', 'siw' ),
			'label'    => __( 'Verbergen', 'siw' ),
			'group'    => self::STYLE_GROUP,
			'type'     => 'checkbox',
			'priority' => 10,
		];
		$fields[ self::STYLE_FIELD_HIDE_ON_TABLET ] = [
			'name'     => '<span class="dashicons dashicons-tablet"></span>' . __( 'Tablet', 'siw' ),
			'label'    => __( 'Verbergen', 'siw' ),
			'group'    => self::STYLE_GROUP,
			'type'     => 'checkbox',
			'priority' => 20,
		];
		$fields[ self::STYLE_FIELD_HIDE_ON_DESKTOP ] = [
			'name'     => '<span class="dashicons dashicons-desktop"></span>' . __( 'Desktop', 'siw' ),
			'label'    => __( 'Verbergen', 'siw' ),
			'group'    => self::STYLE_GROUP,
			'type'     => 'checkbox',
			'priority' => 30,
		];
		return $fields;
	}

	/** {@inheritDoc} */
	public function set_style_attributes( array $style_attributes, array $style_args ): array {
		if ( isset( $style_args[ self::STYLE_FIELD_HIDE_ON_MOBILE ] ) && true === $style_args[ self::STYLE_FIELD_HIDE_ON_MOBILE ] ) {
			$style_attributes['class'][] = CSS::HIDE_ON_MOBILE_CLASS;
		}
		if ( isset( $style_args[ self::STYLE_FIELD_HIDE_ON_TABLET ] ) && true === $style_args[ self::STYLE_FIELD_HIDE_ON_TABLET ] ) {
			$style_attributes['class'][] = CSS::HIDE_ON_TABLET_CLASS;
		}
		if ( isset( $style_args[ self::STYLE_FIELD_HIDE_ON_DESKTOP ] ) && true === $style_args[ self::STYLE_FIELD_HIDE_ON_DESKTOP ] ) {
			$style_attributes['class'][] = CSS::HIDE_ON_DESKTOP_CLASS;
		}
		return $style_attributes;
	}
}
