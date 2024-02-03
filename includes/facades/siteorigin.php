<?php declare(strict_types=1);

namespace SIW\Facades;

class SiteOrigin {

	public static function panels_setting( string $key ): mixed {
		if ( ! function_exists( 'siteorigin_panels_setting' ) ) {
			return null;
		}
		return siteorigin_panels_setting( $key );
	}

	public static function widget_register( string $id, string $path, bool|string $class_name ): void {
		if ( ! function_exists( 'siteorigin_widget_register' ) ) {
			return;
		}
		siteorigin_widget_register( $id, $path, $class_name );
	}

	public static function activate_widget( string $id ) {
		if ( ! class_exists( \SiteOrigin_Widgets_Bundle::class ) ) {
			return;
		}
		\SiteOrigin_Widgets_Bundle::single()->activate_widget( $id );
	}
}
