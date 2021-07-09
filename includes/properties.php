<?php declare(strict_types=1);

namespace SIW;

/**
 * Properties
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
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

	/** Inschrijfgeld Groepsproject (student) */
	const WORKCAMP_FEE_STUDENT = 225;

	/** Inschrijfgeld Groepsproject (regulier) */
	const WORKCAMP_FEE_REGULAR = 275;

	/** Inschrijfgeld Groepsproject (student) - korting */
	const WORKCAMP_FEE_STUDENT_SALE = 149;

	/** Inschrijfgeld Groepsproject (regulier) - korting */
	const WORKCAMP_FEE_REGULAR_SALE = 199;

	/** Inschrijfgeld Op Maat (student) */
	const TAILOR_MADE_FEE_STUDENT = 349;

	/** Inschrijfgeld Op Maat (regulier) */
	const TAILOR_MADE_FEE_REGULAR = 399;

	/** Inschrijfgeld Op Maat (duo) */
	const TAILOR_MADE_FEE_DUO = 550;

	/** Inschrijfgeld Op Maat (familie) */
	const TAILOR_MADE_FEE_FAMILY = 750;

	/** Inschrijfgeld Op Maat (student) - korting */
	const TAILOR_MADE_FEE_STUDENT_SALE = 324;

	/** Inschrijfgeld Op Maat (regulier) - korting */
	const TAILOR_MADE_FEE_REGULAR_SALE = 374;

	/** Inschrijfgeld Schoolproject */
	const SCHOOL_PROJECT_FEE = 125;

	/** ESC borg */
	const ESC_DEPOSIT = 149;

	/** Studentenkorting (in EUR) */
	const STUDENT_DISCOUNT = 50;

	/** Kortingspercentage voor 2e project */
	const DISCOUNT_SECOND_PROJECT = 25;

	/** Kleurcode primaire kleur */
	const PRIMARY_COLOR = '#ff9900';

	/** Kleurcode secundaire kleur */
	const SECONDARY_COLOR = '#59ab9c';

	/** Kleurcode voor tekst */
	const FONT_COLOR = '#444';

	/** Kleurcode voor lichte tekst */
	const FONT_COLOR_LIGHT = '#555';

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
