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
	const WHATSAPP = '06-82208746';

	/** Volledig WhatsApp-nummer (voor API) */
	const WHATSAPP_FULL = '31682208746';

	/** KVK-nummer */
	const KVK = '41165368';

	/** IBAN */
	const IBAN = 'NL28 INGB 0000 0040 75';

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

	/** Fee voor STV-projecten */
	const STV_PROJECT_FEE = 295;

	/** Fee voor MTV-projecten */
	const MTV_PROJECT_FEE = 375;

	/** Fee voor LTV-projecten */
	const LTV_PROJECT_FEE = 475;

	/** Fee voor Nederlandse projecten */
	const DUTCH_PROJECT_FEE = 195;

	/** Bedrag studentenkorting  */
	const STUDENT_DISCOUNT_AMOUNT = 50;

	/** Inschrijfgeld Schoolproject */
	const SCHOOL_PROJECT_FEE = 125;

	/** ESC borg */
	const ESC_DEPOSIT = 149;

	/** Kortingspercentage voor 2e project */
	const DISCOUNT_SECOND_PROJECT = 25;

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
	public static function get_all() : array {
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
