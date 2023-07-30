<?php declare(strict_types=1);

namespace SIW;

/**
 * Properties
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Properties {

	/** Naam */
	const NAME = 'SIW Internationale Vrijwilligersprojecten';

	/** Statutaire naam */
	const STATUTORY_NAME = 'Stichting Internationale Werkkampen';

	/** E-mailadres */
	const EMAIL = 'info@siw.nl';

	/** Oprichtingsdatum */
	const FOUNDING_DATE = '1953-10-24';

	/** Telefoonnummer */
	const PHONE = '030-2317721';

	/** Internationaal telefoonnummer */
	const PHONE_INTERNATIONAL = '+31 30 2317721';

	/** WhatsApp-nummer */
	const WHATSAPP = '06-27403759';

	/** Volledig WhatsApp-nummer (voor API) */
	const WHATSAPP_FULL = '31627403759';

	/** KVK-nummer */
	const KVK = '41165368';

	/** IBAN */
	const IBAN = 'NL65 TRIO 0320 4721 24';

	/** RSIN */
	const RSIN = '002817482';

	/** Adres */
	const ADDRESS = 'Willemstraat 7';

	/** Postcode */
	const POSTCODE = '3511 RJ';

	/** Stad */
	const CITY = 'Utrecht';

	/** Maximum aantal bestuursleden */
	const MAX_BOARD_MEMBERS = 9;

	/** Maximum aantal jaarverslagen */
	const MAX_ANNUAL_REPORTS = 5;

	/** Maximale afmeting voor afbeelding */
	const MAX_IMAGE_SIZE = 1920;

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
