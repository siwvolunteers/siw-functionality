<?php declare(strict_types=1);

namespace SIW;

/**
 * Properties
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Properties {

	/**
	 * Naam
	 *
	 * @var string
	 */
	const NAME = 'SIW Internationale Vrijwilligersprojecten';

	/**
	 * Statutaire naam
	 *
	 * @var string
	 */
	const STATUTORY_NAME = 'Stichting Internationale Werkkampen';

	/**
	 * E-mailadres
	 *
	 * @var string
	 */
	const EMAIL = 'info@siw.nl';

	/**
	 * Oprichtingsdatum
	 *
	 * @var string
	*/
	const FOUNDING_DATE = '1953-10-24';

	/**
	 * Telefoonnummer
	 *
	 * @var string
	 */
	const PHONE = '030-2317721';

	/**
	 * Internationaal telefoonnummer
	 *
	 * @var string
	 */
	const PHONE_INTERNATIONAL = '+31 30 2317721';

	/**
	 * WhatsApp-nummer
	 *
	 * @var string
	 */
	const WHATSAPP = '06-82208746';

	/**
	 * Volledig WhatsApp-nummer (voor API)
	 *
	 * @var string
	 */
	const WHATSAPP_FULL = '0031682208746';

	/**
	 * KVK-nummer
	 *
	 * @var string
	 */
	const KVK = '41165368';

	/**
	 * IBAN
	 *
	 * @var string
	 */
	const IBAN = 'NL28 INGB 0000 0040 75';

	/**
	 * RSIN
	 *
	 * @var string
	 */
	const RSIN = '002817482';

	/**
	 * Adres
	 *
	 * @var string
	 */
	const ADDRESS = 'Willemstraat 7';

	/**
	 * Postcode
	 *
	 * @var string
	 */
	const POSTCODE = '3511 RJ';

	/**
	 * Stad
	 *
	 * @var string
	 */
	const CITY = 'Utrecht';

	/**
	 * Maximum aantal bestuursleden
	 *
	 * @var int
	 */
	const MAX_BOARD_MEMBERS = 9;

	/**
	 * Maximum aantal jaarverslagen
	 *
	 * @var int
	 */
	const MAX_ANNUAL_REPORTS = 5;

	/**
	 * Inschrijfgeld Groepsproject (student)
	 *
	 * @var int
	 */
	const WORKCAMP_FEE_STUDENT = 225;

	/**
	 * Inschrijfgeld Groepsproject (regulier)
	 *
	 * @var int
	 */
	const WORKCAMP_FEE_REGULAR = 275;

	/**
	 * Inschrijfgeld Groepsproject (student) - korting
	 *
	 * @var int
	 */
	const WORKCAMP_FEE_STUDENT_SALE = 149;

	/**
	 * Inschrijfgeld Groepsproject (regulier) - korting
	 *
	 * @var int
	 */
	const WORKCAMP_FEE_REGULAR_SALE = 199;

	/**
	 * Inschrijfgeld Op Maat (student)
	 *
	 * @var int
	 */
	const TAILOR_MADE_FEE_STUDENT = 349;

	/**
	 * Inschrijfgeld Op Maat (regulier)
	 *
	 * @var int
	 */
	const TAILOR_MADE_FEE_REGULAR = 399;

	/**
	 * Inschrijfgeld Op Maat (duo)
	 *
	 * @var int
	 */
	const TAILOR_MADE_FEE_DUO = 550;

	/**
	 * Inschrijfgeld Op Maat (familie)
	 *
	 * @var int
	 */
	const TAILOR_MADE_FEE_FAMILY = 750;

	/**
	 * Inschrijfgeld Op Maat (student) - korting
	 *
	 * @var int
	 */
	const TAILOR_MADE_FEE_STUDENT_SALE = 324;

	/**
	 * Inschrijfgeld Op Maat (regulier) - korting
	 *
	 * @var int
	 */
	const TAILOR_MADE_FEE_REGULAR_SALE = 374;

	/**
	 * Inschrijfgeld Schoolproject
	 *
	 * @var int
	 */
	const SCHOOL_PROJECT_FEE = 125;

	/**
	 * ESC borg
	 *
	 * @var int
	 */
	const ESC_DEPOSIT = 149;

	/**
	 * Kortingspercentage voor 2e project
	 *
	 * @var int
	 */
	const DISCOUNT_SECOND_PROJECT = 25;

	/**
	 * Kleurcode primaire kleur
	 *
	 * @var string
	 */
	const PRIMARY_COLOR = '#ff9900';

	/**
	 * Kleurcode secundaire kleur
	 *
	 * @var string
	 */
	const SECONDARY_COLOR = '#59ab9c';

	/**
	 * Kleurcode voor tekst
	 *
	 * @var string
	 */
	const FONT_COLOR = '#444';

	/**
	 * Kleurcode voor lichte tekst
	 *
	 * @var string
	 */
	const FONT_COLOR_LIGHT = '#555';

	/**
	 * Maximale afmeting voor afbeelding
	 *
	 * @var int
	 */
	const MAX_IMAGE_SIZE = 1920;

	/**
	 * Geeft waarde van property terug
	 *
	 * @param string $property
	 * @return string
	 */
	public static function get( string $property ) {
		$property = strtoupper( $property );
		if ( defined( 'self::' . $property ) ) {
			return constant('self::' . $property );
		}
		return null;
	}

	/**
	 * Geeft array met properties terug
	 *
	 * @return array
	 */
	static public function get_all() : array {
		$reflectionClass = new \ReflectionClass( __CLASS__ );
		$constants = $reflectionClass->getConstants();
		$configuration = [];
		foreach ( $constants as $name => $value ) {
			$reflectionConstant = new \ReflectionClassConstant( __CLASS__, $name );
			$comment = $reflectionConstant->getDocComment();
			
			//Haal naam uit docblock
			$description = trim(
				str_replace(
					'*',
					'',
					explode( PHP_EOL, $comment)[1]
				)
			);
			$configuration[ $name ] = [ 'name' => $name, 'value' => $value, 'description' => $description ];
		}

		return $configuration;
	}
}
