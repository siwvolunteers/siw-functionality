<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Attributes\Filter;
use SIW\Base;


/**
 * Klasse om externe asset (JS/CSS) te registreren
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
abstract class External_Asset extends Base {

	/** Genereert assets handle */
	public static function get_assets_handle(): string {
		return strtolower( str_replace( [ '\\', '_' ], '-', static::class ) );
	}

	/** Geeft gewenste versienummer terug */
	abstract protected static function get_version_number(): ?string;

	/** Geeft URL van script terug */
	abstract protected static function get_script_url(): ?string;

	/** Geeft URL van style terug */
	abstract protected static function get_style_url(): ?string;

	/** Geeft terug of er een script is */
	protected static function has_script(): bool {
		return null !== static::get_script_url();
	}

	/** Geeft terug of er een style is */
	protected static function has_style(): bool {
		return null !== static::get_style_url();
	}

	protected static function get_script_dependencies(): array {
		return [];
	}

	#[Filter( 'wp_enqueue_scripts' )]
	#[Filter( 'admin_enqueue_scripts' )]
	public function register_script() {
		if ( ! static::has_script() ) {
			return;
		}

		wp_register_script(
			static::get_assets_handle(),
			static::get_script_url(),
			static::get_script_dependencies(),
			null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			true
		);
	}

	#[Filter( 'wp_enqueue_scripts' )]
	#[Filter( 'admin_enqueue_scripts' )]
	public function register_style() {

		if ( ! static::has_style() ) {
			return;
		}

		wp_register_style(
			static::get_assets_handle(),
			$this->get_style_url(),
			[],
			null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		);
	}

	#[Filter( 'rocket_dns_prefetch' )]
	#[Filter( 'rocket_minify_excluded_external_js' )]
	#[Filter( 'rocket_exclude_css' )]
	public static function exclude_external_asset_domains( array $exclusions ): array {
		if ( null !== static::get_domain() ) {
			$exclusions[] = static::get_domain();
		}
		return $exclusions;
	}

	/** Geeft domain van externe asset terug */
	protected static function get_domain(): ?string {

		if ( null !== static::get_script_url() ) {
			return wp_parse_url( static::get_script_url(), PHP_URL_HOST );
		} elseif ( null !== static::get_style_url() ) {
			return wp_parse_url( static::get_style_url(), PHP_URL_HOST );
		} else {
			return null;
		}
	}
}
