<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Attributes\Add_Action;
use SIW\Data\Tag_Attribute;
use SIW\Helpers\Template;

abstract class NPM_Asset extends External_Asset {

	private const JSDELIVR_URL = 'https://cdn.jsdelivr.net/';

	abstract protected static function get_npm_package(): string;

	abstract protected static function get_script_file(): ?string;

	abstract protected static function get_style_file(): ?string;

	abstract protected static function get_script_sri(): ?string;

	abstract protected static function get_style_sri(): ?string;

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

	#[Add_Action( 'wp_enqueue_scripts', 11 )]
	#[Add_Action( 'admin_enqueue_scripts', 11 )]
	public function add_script_data() {

		if ( ! static::has_script() ) {
			return;
		}
		wp_script_add_data(
			static::get_asset_handle(),
			Tag_Attribute::INTEGRITY->value,
			static::get_script_sri()
		);

		wp_script_add_data(
			static::get_asset_handle(),
			Tag_Attribute::CROSSORIGIN->value,
			'anonymous'
		);
	}

	#[Add_Action( 'wp_enqueue_scripts', 11 )]
	#[Add_Action( 'admin_enqueue_scripts', 11 )]
	public function add_style_date() {
		if ( ! static::has_style() ) {
			return;
		}

		wp_style_add_data(
			static::get_asset_handle(),
			Tag_Attribute::CROSSORIGIN->value,
			'anonymous'
		);

		wp_style_add_data(
			static::get_asset_handle(),
			Tag_Attribute::INTEGRITY->value,
			static::get_style_sri()
		);
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
