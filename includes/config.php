<?php declare(strict_types=1);

namespace SIW;

class Config {

	public static function get_constant_value( string $name, mixed $default_value = null ): mixed {
		if ( defined( $name ) ) {
			return constant( $name );
		}
		return $default_value;
	}

	public static function get_google_maps_js_api_key(): string {
		return self::get_constant_value( 'SIW_GOOGLE_MAPS_JS_API_KEY', '' );
	}

	public static function get_gtm_container_id(): ?string {
		return self::get_constant_value( 'SIW_GTM_CONTAINER_ID' );
	}

	public static function get_gtm_auth(): ?string {
		return self::get_constant_value( 'SIW_GTM_AUTH' );
	}

	public static function get_gtm_preview(): ?string {
		return self::get_constant_value( 'SIW_GTM_PREVIEW' );
	}

	public static function get_meta_pixel_id(): ?string {
		return self::get_constant_value( 'SIW_META_PIXEL_ID' );
	}

	public static function get_fixer_io_api_key(): string {
		return self::get_constant_value( 'SIW_FIXER_IO_API_KEY', '' );
	}

	public static function get_mailjet_api_key(): string {
		return self::get_constant_value( 'SIW_MAILJET_API_KEY', '' );
	}

	public static function get_mailjet_secret_key(): string {
		return self::get_constant_value( 'SIW_MAILJET_SECRET_KEY', '' );
	}

	public static function get_mailjet_newsletter_list_id(): ?int {
		return self::get_constant_value( 'SIW_MAILJET_NEWSLETTER_LIST_ID' );
	}

	public static function get_plato_organization_webkey(): string {
		return self::get_constant_value( 'SIW_PLATO_ORGANIZATION_WEBKEY', '' );
	}

	public static function get_plato_download_images(): bool {
		return self::get_constant_value( 'SIW_PLATO_DOWNLOAD_IMAGES', false );
	}

	public static function get_plato_export_applications(): bool {
		return self::get_constant_value( 'SIW_PLATO_EXPORT_APPLICATIONS', false );
	}

	public static function get_smtp_enabled(): bool {
		return self::get_constant_value( 'SIW_SMTP_ENABLED', false );
	}

	public static function get_smtp_host(): string {
		return self::get_constant_value( 'SIW_SMTP_HOST' );
	}

	public static function get_smtp_port(): ?int {
		return self::get_constant_value( 'SIW_SMTP_PORT' );
	}

	public static function get_smtp_username(): ?string {
		return self::get_constant_value( 'SIW_SMTP_USERNAME' );
	}

	public static function get_smtp_password(): ?string {
		return self::get_constant_value( 'SIW_SMTP_PASSWORD' );
	}

	public static function get_smtp_encryption(): ?string {
		return self::get_constant_value( 'SIW_SMTP_ENCRYPTION' );
	}

	public static function get_dkim_enabled(): bool {
		return self::get_constant_value( 'SIW_DKIM_ENABLED', false );
	}

	public static function get_dkim_selector(): ?string {
		return self::get_constant_value( 'SIW_DKIM_SELECTOR' );
	}

	public static function get_dkim_domain(): ?string {
		return self::get_constant_value( 'SIW_DKIM_DOMAIN' );
	}

	public static function get_dkim_passphrase(): ?string {
		return self::get_constant_value( 'SIW_DKIM_PASSPHRASE' );
	}

	public static function get_dkim_private_key_file_path(): ?string {
		return self::get_constant_value( 'SIW_DKIM_PRIVATE_KEY_FILE_PATH' );
	}

	public static function get_stv_project_fee(): int {
		return self::get_constant_value( 'SIW_STV_PROJECT_FEE' );
	}

	public static function get_mtv_project_fee(): int {
		return self::get_constant_value( 'SIW_MTV_PROJECT_FEE' );
	}

	public static function get_ltv_project_fee(): int {
		return self::get_constant_value( 'SIW_LTV_PROJECT_FEE' );
	}

	public static function get_dutch_project_fee(): int {
		return self::get_constant_value( 'SIW_DUTCH_PROJECT_FEE' );
	}

	public static function get_student_discount_amount(): int {
		return self::get_constant_value( 'SIW_STUDENT_DISCOUNT_AMOUNT' );
	}

	public static function get_school_project_fee(): int {
		return self::get_constant_value( 'SIW_STUDENT_DISCOUNT_AMOUNT' );
	}

	public static function get_esc_deposit(): int {
		return self::get_constant_value( 'SIW_ESC_DEPOSIT' );
	}

	public static function get_discount_percentage_second_project(): int {
		return self::get_constant_value( 'SIW_DISCOUNT_PERCENTAGE_SECOND_PROJECT' );
	}
}
