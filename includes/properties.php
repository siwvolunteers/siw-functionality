<?php declare(strict_types=1);

namespace SIW;

/**
 * Properties
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Properties {

	/** Naam */
	public const NAME = 'SIW Internationale Vrijwilligersprojecten';

	/** Statutaire naam */
	public const STATUTORY_NAME = 'Stichting Internationale Werkkampen';

	/** E-mailadres */
	public const EMAIL = 'info@siw.nl';

	/** Oprichtingsdatum */
	public const FOUNDING_DATE = '1953-10-24';

	/** Telefoonnummer */
	public const PHONE = '030-2317721';

	/** Internationaal telefoonnummer */
	public const PHONE_INTERNATIONAL = '+31 30 2317721';

	/** WhatsApp-nummer */
	public const WHATSAPP = '06-27403759';

	/** Volledig WhatsApp-nummer (voor API) */
	public const WHATSAPP_FULL = '31627403759';

	/** KVK-nummer */
	public const KVK = '41165368';

	/** IBAN */
	public const IBAN = 'NL65 TRIO 0320 4721 24';

	/** RSIN */
	public const RSIN = '002817482';

	/** Adres */
	public const ADDRESS = 'Willemstraat 7';

	/** Postcode */
	public const POSTCODE = '3511 RJ';

	/** Stad */
	public const CITY = 'Utrecht';

	/** Maximum aantal bestuursleden */
	public const MAX_BOARD_MEMBERS = 9;

	/** Maximum aantal jaarverslagen */
	public const MAX_ANNUAL_REPORTS = 5;

	/** Maximale afmeting voor afbeelding */
	public const MAX_IMAGE_SIZE = 1920;

	/** Geeft waarde van property terug */
	public static function get( string $property ) {
		$property = strtoupper( $property );
		if ( defined( 'self::' . $property ) ) {
			return constant( 'self::' . $property );
		}
		return null;
	}

	/** Geeft array met properties terug */
	public static function get_all(): array {
		$reflection_class = new \ReflectionClass( __CLASS__ );
		$constants = $reflection_class->getConstants();
		$configuration = [];
		foreach ( $constants as $name => $value ) {
			$reflection_constant = new \ReflectionClassConstant( __CLASS__, $name );
			$comment = $reflection_constant->getDocComment();

			// Haal beschrijving uit docblock
			$regex = '/\/\*\*\s*(.*)\s\*\//m';
			if ( 1 === preg_match( $regex, $comment, $matches ) ) {
				$description = $matches[1];
			} else {
				$description = '';
			}

			$configuration[ $name ] = [
				'name'        => $name,
				'value'       => $value,
				'description' => $description,
			];
		}

		return $configuration;
	}
}
