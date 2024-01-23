<?php declare(strict_types=1);

namespace SIW\Traits;

use SIW\Data\Asset_Type;

trait Class_Assets {
	public static function get_asset_handle(): string {
		return strtolower( str_replace( [ '\\', '_' ], '-', static::class ) );
	}

	protected static function get_style_asset_url(): string {
		return self::get_asset_url( Asset_Type::CSS );
	}

	protected static function get_script_asset_url(): string {
		return self::get_asset_url( Asset_Type::JS );
	}

	protected static function get_style_asset_path(): string {
		return self::get_asset_path( Asset_Type::CSS );
	}

	protected static function get_asset_url( Asset_Type $type ): string {
		return SIW_ASSETS_URL . self::get_asset_file_base( $type );
	}

	protected static function get_asset_path( Asset_Type $type ): string {
		return wp_normalize_path( SIW_ASSETS_DIR . self::get_asset_file_base( $type ) );
	}

	public static function enqueue_class_style( array $dependencies = [] ) {
		wp_register_style( self::get_asset_handle(), self::get_style_asset_url(), $dependencies, SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_asset_handle(), 'path', self::get_style_asset_path() );
		wp_enqueue_style( self::get_asset_handle() );
	}

	public static function enqueue_class_script( array $dependencies ) {
		wp_register_script(
			self::get_asset_handle(),
			self::get_script_asset_url(),
			$dependencies,
			SIW_PLUGIN_VERSION,
			true
		);
		wp_enqueue_script( self::get_asset_handle() );
	}

	protected static function get_asset_file_base( Asset_Type $type ): string {
		$class_name = str_replace( 'SIW\\', '', static::class );
		return sprintf(
			'%1$s/%2$s.%1$s',
			$type->value,
			strtolower( str_replace( '_', '-', $class_name ) )
		);
	}
}
