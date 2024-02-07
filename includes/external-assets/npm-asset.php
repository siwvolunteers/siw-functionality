<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Helpers\Template;

abstract class NPM_Asset extends External_Asset {

	private const JSDELIVR_URL = 'https://cdn.jsdelivr.net/';

	abstract protected static function get_npm_package(): string;

	protected static function get_script_file(): ?string {
		return null;
	}

	protected static function get_style_file(): ?string {
		return null;
	}

	#[\Override]
	protected static function get_script_url(): ?string {
		if ( null === static::get_script_file() ) {
			return null;
		}
		return static::get_npm_asset_url( static::get_script_file() );
	}

	#[\Override]
	protected static function get_style_url(): ?string {
		if ( null === static::get_style_file() ) {
			return null;
		}
		return static::get_npm_asset_url( static::get_style_file() );
	}

	protected static function get_npm_asset_url( string $file ): string {
		return Template::create()
			->set_template( '{{ npm_cdn_url }}/npm/{{ package }}@{{ version }}/{{ file }}' )
			->set_context(
				[
					'npm_cdn_url' => untrailingslashit( self::JSDELIVR_URL ),
					'package'     => static::get_npm_package(),
					'version'     => static::get_version_number(),
					'file'        => $file,
				]
			)
			->parse_template();
	}
}
