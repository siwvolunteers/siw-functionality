<?php declare(strict_types=1);

namespace SIW;

/**
 * Properties
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
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
	const WHATSAPP_FULL = '0031682208746';

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
	const STV_FEE = 295;

	/** Fee voor MTV-projecten */
	const MTV_FEE = 375;

	/** Fee voor LTV-projecten */
	const LTV_FEE = 475;

	/** Fee voor Nederlandse projecten */
	const DUTCH_PROJECTS_FEE = 195;

	/** Bedrag studentenkorting  */
	const STUDENT_DISCOUNT_AMOUNT = 50;

	/** @deprecated */
	const WORKCAMP_FEE_STUDENT = 225;

	/** @deprecated */
	const WORKCAMP_FEE_REGULAR = 275;

	/** @deprecated */
	const WORKCAMP_FEE_STUDENT_SALE = 149;

	/** @deprecated */
	const WORKCAMP_FEE_REGULAR_SALE = 199;

	/** @deprecated */
	const TAILOR_MADE_FEE_STUDENT = 349;

	/** @deprecated */
	const TAILOR_MADE_FEE_REGULAR = 399;

	/** @deprecated */
	const TAILOR_MADE_FEE_DUO = 550;

	/** @deprecated */
	const TAILOR_MADE_FEE_FAMILY = 750;

	/** @deprecated */
	const TAILOR_MADE_FEE_STUDENT_SALE = 324;

	/** @deprecated */
	const TAILOR_MADE_FEE_REGULAR_SALE = 374;

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
			return constant('self::' . $property );
		}
		return null;
	}

	/** Geeft array met properties terug */
	static public function get_all() : array {
		$reflectionClass = new \ReflectionClass( __CLASS__ );
		$constants = $reflectionClass->getConstants();
		$configuration = [];
		foreach ( $constants as $name => $value ) {
			$reflectionConstant = new \ReflectionClassConstant( __CLASS__, $name );
			$comment = $reflectionConstant->getDocComment();
			
			//Haal beschrijving uit docblock
			$regex = '/\/\*\*\s*(.*)\s\*\//m';
			if ( 1 == preg_match( $regex, $comment, $matches ) ) {
				$description = $matches[1];
			}
			else {
				$description = '';
			}

			$configuration[ $name ] = [ 'name' => $name, 'value' => $value, 'description' => $description ];
		}

		return $configuration;
	}
}
