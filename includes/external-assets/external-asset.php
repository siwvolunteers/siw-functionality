<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Tag_Attribute;
use SIW\Traits\Class_Assets;

abstract class External_Asset extends Base {

	use Class_Assets;

	abstract protected static function get_version_number(): ?string;
	abstract protected static function get_script_url(): ?string;
	abstract protected static function get_style_url(): ?string;

	protected static function get_cookie_category(): ?string {
		return null;
	}

	protected static function has_script(): bool {
		return null !== static::get_script_url();
	}

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
			static::get_asset_handle(),
			static::get_script_url(),
			static::get_script_dependencies(),
			null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			true
		);

		if ( null !== static::get_cookie_category() ) {
			wp_script_add_data(
				static::get_asset_handle(),
				Tag_Attribute::TYPE,
				'text/plain'
			);

			wp_script_add_data(
				static::get_asset_handle(),
				Tag_Attribute::COOKIE_CATEGORY,
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
			static::get_asset_handle(),
			$this->get_style_url(),
			[],
			null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		);
	}
}
