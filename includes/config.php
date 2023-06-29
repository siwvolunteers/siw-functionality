<?php declare(strict_types=1);

namespace SIW;

/**
 * Configuratie uit wp-config.php constantes halen
 *
 * @copyright 2022-2023 SIW Internationale Vrijwilligersprojecten
 */
class Config {

	/** Geeft waarde van constante `$name` terug indien gedefinieerd, anders `$default` */
	public static function get_constant_value( string $name, mixed $default = null ): mixed {
		if ( defined( $name ) ) {
			return constant( $name );
		}
		return $default;
	}

	/** Geeft api key voor Google Maps terug */
	public static function get_google_maps_api_key(): string {
		return self::get_constant_value( 'SIW_GOOGLE_MAPS_API_KEY', '' );
	}

	/** Geeft api key voor Google Maps (client side) terug */
	public static function get_google_maps_js_api_key(): string {
		return self::get_constant_value( 'SIW_GOOGLE_MAPS_JS_API_KEY', '' );
	}

	public static function get_google_analytics_measurement_id(): ?string {
		return self::get_constant_value( 'SIW_GOOGLE_ANALYTICS_MEASUREMENT_ID' );
	}

	/** Geeft Meta Pixel ID terug */
	public static function get_meta_pixel_id(): ?string {
		return self::get_constant_value( 'SIW_META_PIXEL_ID' );
	}

	/** Geeft api key van fixer.io terug */
	public static function get_fixer_io_api_key(): string {
		return self::get_constant_value( 'SIW_FIXER_IO_API_KEY', '' );
	}

	/** Geeft api key van Mailjet terug */
	public static function get_mailjet_api_key(): string {
		return self::get_constant_value( 'SIW_MAILJET_API_KEY', '' );
	}

	/** Geeft secret key van Mailjet terug */
	public static function get_mailjet_secret_key(): string {
		return self::get_constant_value( 'SIW_MAILJET_SECRET_KEY', '' );
	}

	/** Geeft ID van MailJet lijst voor nieuwsbrief terug */
	public static function get_mailjet_newsletter_list_id(): ?int {
		return self::get_constant_value( 'SIW_MAILJET_NEWSLETTER_LIST_ID' );
	}

	/** Geeft Plato organization ID terug*/
	public static function get_plato_organization_webkey(): string {
		return self::get_constant_value( 'SIW_PLATO_ORGANIZATION_WEBKEY', '' );
	}

	/** Geeft aan of Plato afbeeldingen gedownload moeten worden */
	public static function get_plato_download_images(): bool {
		return self::get_constant_value( 'SIW_PLATO_DOWNLOAD_IMAGES', false );
	}

	/** Geeft aan of aanmeldingen naar plato geëxporteerd moeten worden */
	public static function get_plato_export_applications(): bool {
		return self::get_constant_value( 'SIW_PLATO_EXPORT_APPLICATIONS', false );
	}

	/** Geeft aan of STMP ingeschakeld is */
	public static function get_smtp_enabled(): bool {
		return self::get_constant_value( 'SIW_SMTP_ENABLED', false );
	}

	/** Geeft SMTP host terug */
	public static function get_smtp_host(): string {
		return self::get_constant_value( 'SIW_SMTP_HOST' );
	}

	/** Geeft SMTP port terug */
	public static function get_smtp_port(): ?int {
		return self::get_constant_value( 'SIW_SMTP_PORT' );
	}

	/** Geeft SMTP username terug */
	public static function get_smtp_username(): ?string {
		return self::get_constant_value( 'SIW_SMTP_USERNAME' );
	}

	/** Geeft SMTP wachtwoord terug */
	public static function get_smtp_password(): ?string {
		return self::get_constant_value( 'SIW_SMTP_PASSWORD' );
	}

	/** Geeft SMTP encryptie terug */
	public static function get_smtp_encryption(): ?string {
		return self::get_constant_value( 'SIW_SMTP_ENCRYPTION' );
	}

	/** Geeft aan of DKIM ingeschakeld is */
	public static function get_dkim_enabled(): bool {
		return self::get_constant_value( 'SIW_DKIM_ENABLED', false );
	}

	/** Geeft DKIM selector terug */
	public static function get_dkim_selector(): ?string {
		return self::get_constant_value( 'SIW_DKIM_SELECTOR' );
	}

	/** Geeft DKIM domein terug */
	public static function get_dkim_domain(): ?string {
		return self::get_constant_value( 'SIW_DKIM_DOMAIN' );
	}

	/** Geeft DKIM passphrase terug */
	public static function get_dkim_passphrase(): ?string {
		return self::get_constant_value( 'SIW_DKIM_PASSPHRASE' );
	}

	/** Geeft path naar DKIM private key terug */
	public static function get_dkim_private_key_file_path(): ?string {
		return self::get_constant_value( 'SIW_DKIM_PRIVATE_KEY_FILE_PATH' );
	}

	/** Geeft STV tarief terug */
	public static function get_stv_project_fee(): int {
		return self::get_constant_value( 'SIW_STV_PROJECT_FEE' );
	}

	/** Geeft MTV tarief terug */
	public static function get_mtv_project_fee(): int {
		return self::get_constant_value( 'SIW_MTV_PROJECT_FEE' );
	}

	/** Geeft LTV tarief terug */
	public static function get_ltv_project_fee(): int {
		return self::get_constant_value( 'SIW_LTV_PROJECT_FEE' );
	}

	/** Geeft NP tarief terug */
	public static function get_dutch_project_fee(): int {
		return self::get_constant_value( 'SIW_DUTCH_PROJECT_FEE' );
	}

	/** Geeft bedrag studentenkorting terug */
	public static function get_student_discount_amount(): int {
		return self::get_constant_value( 'SIW_STUDENT_DISCOUNT_AMOUNT' );
	}

	/** Geef inschrijfgeld voor  */
	public static function get_school_project_fee(): int {
		return self::get_constant_value( 'SIW_STUDENT_DISCOUNT_AMOUNT' );
	}

	/** Geeft ESC borg terug */
	public static function get_esc_deposit(): int {
		return self::get_constant_value( 'SIW_ESC_DEPOSIT' );
	}

	/** Geeft kortingspercentage voor tweede project terug */
	public static function get_discount_percentage_second_project(): int {
		return self::get_constant_value( 'SIW_DISCOUNT_PERCENTAGE_SECOND_PROJECT' );
	}
}
