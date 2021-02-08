<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Row_Style_Group as Row_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Cell_Style_Group as Cell_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Widget_Style_Group as Widget_Style_Group_Interface;

use SIW\Util\CSS;

/**
 * Zichtbaarheidsopties voor Page Builder
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
class Visibility implements Row_Style_Group_Interface, Cell_Style_Group_Interface, Widget_Style_Group_Interface {

	/** Style groep */
	const STYLE_GROUP = 'siw-visibility';

	/** Style fields voor verbergen op mobiel */
	const STYLE_FIELD_HIDE_ON_MOBILE = 'hide_on_mobile';

	/** Style fields voor verbergen op tablet */
	const STYLE_FIELD_HIDE_ON_TABLET = 'hide_on_tablet';

	/** Style fields voor verbergen op desktop */
	const STYLE_FIELD_HIDE_ON_DESKTOP = 'hide_on_desktop';

	/**
	 * {@inheritDoc}
	 */
	public function add_style_group( array $groups ) : array {
		$groups[ self::STYLE_GROUP ] = [
			'name'     => __( 'Zichtbaarheid', 'siw' ),
			'priority' => 99,
		];
		return $groups;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_style_fields( array $fields ) : array {
		$fields[ self::STYLE_FIELD_HIDE_ON_MOBILE ] = [
			'name'     => '<span class="dashicons dashicons-smartphone"></span>' . __( 'Mobiel', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => self::STYLE_GROUP,
			'type'     => 'checkbox',
			'priority' => 10,
		];
		$fields[ self::STYLE_FIELD_HIDE_ON_TABLET ] = [
			'name'     => '<span class="dashicons dashicons-tablet"></span>' . __( 'Tablet', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => self::STYLE_GROUP,
			'type'     => 'checkbox',
			'priority' => 20,
		];
		$fields[ self::STYLE_FIELD_HIDE_ON_DESKTOP ] = [
			'name'     => '<span class="dashicons dashicons-desktop"></span>' . __( 'Desktop', 'siw'),
			'label'    => __( 'Verbergen', 'siw'),
			'group'    => self::STYLE_GROUP,
			'type'     => 'checkbox',
			'priority' => 30,
		];
		return $fields;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array {
		if ( isset( $style_args[ self::STYLE_FIELD_HIDE_ON_MOBILE ] ) && 1 == $style_args[ self::STYLE_FIELD_HIDE_ON_MOBILE ] ) {
			$style_attributes['class'][] = CSS::HIDE_ON_MOBILE_CLASS;
		}
		if ( isset( $style_args[ self::STYLE_FIELD_HIDE_ON_TABLET ] ) && 1 == $style_args[ self::STYLE_FIELD_HIDE_ON_TABLET ] ) {
			$style_attributes['class'][] = CSS::HIDE_ON_TABLET_CLASS;
		}
		if ( isset( $style_args[ self::STYLE_FIELD_HIDE_ON_DESKTOP ] ) && 1 == $style_args[ self::STYLE_FIELD_HIDE_ON_DESKTOP ] ) {
			$style_attributes['class'][] = CSS::HIDE_ON_DESKTOP_CLASS;
		}
		return $style_attributes;
	}
}
