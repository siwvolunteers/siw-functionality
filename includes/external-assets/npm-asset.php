<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Asset_Attributes;
use SIW\Attributes\Action;
use SIW\Helpers\Template;

/**
 * Klasse om NPM asset (JS/CSS) te registreren
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
abstract class NPM_Asset extends External_Asset {

	const JSDELIVR_URL = 'https://cdn.jsdelivr.net/';

	/** Geeft naam van NPM package terug */
	abstract protected static function get_npm_package(): string;

	abstract protected static function get_script_file(): ?string;

	abstract protected static function get_style_file(): ?string;

	abstract protected static function get_script_sri(): ?string;

	abstract protected static function get_style_sri(): ?string;

	/** {@inheritDoc} */
	protected static function get_script_url(): ?string {
		if ( null === static::get_script_file() ) {
			return null;
		}
		return static::get_asset_url( static::get_script_file() );
	}

	/** {@inheritDoc} */
	protected static function get_style_url(): ?string {
		if ( null === static::get_style_file() ) {
			return null;
		}
		return static::get_asset_url( static::get_style_file() );
	}

	#[Action( 'wp_enqueue_scripts', 11 )]
	#[Action( 'admin_enqueue_scripts', 11 )]
	public function add_script_data() {

		if ( ! static::has_script() ) {
			return;
		}
		wp_script_add_data(
			static::get_assets_handle(),
			Asset_Attributes::INTEGRITY,
			static::get_script_sri()
		);

		wp_script_add_data(
			static::get_assets_handle(),
			Asset_Attributes::CROSSORIGIN,
			'anonymous'
		);
	}

	#[Action( 'wp_enqueue_scripts', 11 )]
	#[Action( 'admin_enqueue_scripts', 11 )]
	public function add_style_date() {
		if ( ! static::has_style() ) {
			return;
		}

		wp_style_add_data(
			static::get_assets_handle(),
			Asset_Attributes::CROSSORIGIN,
			'anonymous'
		);

		wp_style_add_data(
			static::get_assets_handle(),
			Asset_Attributes::INTEGRITY,
			static::get_style_sri()
		);
	}

	/** Bepaal asset url obv package, versienummer en file */
	protected static function get_asset_url( string $file ): string {
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

	/** {@inheritDoc} */
	protected static function get_domain(): string {
		return self::JSDELIVR_URL;
	}
}
