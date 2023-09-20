<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Asset_Attributes;
use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Traits\Assets_Handle;

/**
 * Klasse om externe asset (JS/CSS) te registreren
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
abstract class External_Asset extends Base {

	use Assets_Handle;

	/** Geeft gewenste versienummer terug */
	abstract protected static function get_version_number(): ?string;

	/** Geeft URL van script terug */
	abstract protected static function get_script_url(): ?string;

	/** Geeft URL van style terug */
	abstract protected static function get_style_url(): ?string;

	/** Geeft cookie category terug */
	protected static function get_cookie_category(): ?string {
		return null;
	}

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

	#[Add_Action( 'wp_enqueue_scripts' )]
	#[Add_Action( 'admin_enqueue_scripts' )]
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

		if ( null !== static::get_cookie_category() ) {
			wp_script_add_data(
				static::get_assets_handle(),
				Asset_Attributes::TYPE,
				'text/plain'
			);

			wp_script_add_data(
				static::get_assets_handle(),
				Asset_Attributes::COOKIE_CATEGORY,
				static::get_cookie_category()
			);
		}
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	#[Add_Action( 'admin_enqueue_scripts' )]
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

	#[Add_Filter( 'rocket_dns_prefetch' )]
	#[Add_Filter( 'rocket_minify_excluded_external_js' )]
	#[Add_Filter( 'rocket_exclude_css' )]
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
