<?php

use Ils\AnnotationParser;

/**
 * Properties
 *
 * @package   SIW
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 *
 * @uses      Ils\AnnotationParser
 */
class SIW_Properties {

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
	 * Openingstijd
	 *
	 * @var string
	 */
	const OPENING_TIME = '10:00';

	/**
	 * Sluitingstijd
	 *
	 * @var string
	 */
	const CLOSING_TIME = '17:00';

	/**
	 * Facebook-pagina
	 *
	 * @var string
	 */
	const FACEBOOK_URL = 'https://www.facebook.com/siwvolunteers';

	/**
	 * Twitter-pagina
	 *
	 * @var string
	 */
	const TWITTER_URL = 'https://twitter.com/siwvolunteers';

	/**
	 * LinkedIn-pagina
	 *
	 * @var string
	 */
	const LINKEDIN_URL = 'https://www.linkedin.com/company/siw';

	/**
	 * YouTube-pagina
	 *
	 * @var string
	 */
	const YOUTUBE_URL = 'https://www.youtube.com/user/SIWvolunteerprojects';

	/**
	 * Instagram-pagina
	 *
	 * @var string
	 */
	const INSTAGRAM_URL = 'https://www.instagram.com/siwvolunteers/';

	/**
	 * Minimaal aantal weken voor deadline
	 *
	 * @var int
	 */
	const ESC_WEEKS_BEFORE_DEADLINE = 5;

	/**
	 * Minimaal aantal weken voor vertrek
	 *
	 * @var int
	 */
	const ESC_WEEKS_BEFORE_DEPARTURE = 14;

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
	 * Tijdstip bijwerken groepsprojecten
	 *
	 * @var string
	 */
	const TS_UPDATE_PROJECTS = '1:00';

	/**
	 * Tijdstip bijwerken vrije plaatsen
	 *
	 * @var string
	 */
	const TS_UPDATE_FREE_PLACES = '2:00';

	/**
	 * Tijdstip achtergrondprogramma's
	 *
	 * @var string
	 */
	const TS_SCHEDULED_JOBS = '03:00';

	/**
	 * Tijdstip backup database
	 *
	 * @var string
	 */
	const TS_BACKUP_DB = '04:00';

	/**
	 * Tijdstip backup bestanden
	 *
	 * @var string
	 */
	const TS_BACKUP_FILES = '04:30';

	/**
	 * Tijdstip cache opnieuw opbouwen
	 *
	 * @var string
	 */
	const TS_CACHE_REBUILD = '05:00';

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
	 * Kortingspercentage voor 3e project
	 *
	 * @var int
	 */
	const DISCOUNT_THIRD_PROJECT = 50;

	/**
	 * Kleurcode primaire kleur
	 *
	 * @var string
	 */
	const PRIMARY_COLOR = '#ff9900';

	/**
	 * Kleurcode primaire kleur (hover)
	 *
	 * @var string
	 */
	const PRIMARY_COLOR_HOVER = '#ffcc33';

	/**
	 * Kleurcode secundaire kleur
	 *
	 * @var string
	 */
	const SECONDARY_COLOR = '#59ab9c';

	/**
	 * Kleurcode secundaire kleur (hover)
	 *
	 * @var string
	 */
	const SECONDARY_COLOR_HOVER = '#8cdecf';

	/**
	 * Kleurcode voor tekst
	 *
	 * @var string
	 */
	const FONT_COLOR = '#444';

	/**
	 * Kleurcode voor kaart van continent Europa
	 *
	 * @var string
	 */
	const COLOR_EUROPE = '#007499';

	/**
	 * Kleurcode voor kaart van continent Azië
	 *
	 * @var string
	 */
	const COLOR_ASIA = '#008e3f';

	/**
	 * Kleurcode voor kaart van continent Afrika
	 *
	 * @var string
	 */
	const COLOR_AFRICA = '#e30613';

	/**
	 * Kleurcode voor kaart van continent Latijns-Amerika
	 *
	 * @var string
	 */
	const COLOR_LATIN_AMERICA = '#c7017f';

	/**
	 * Kleurcode voor kaart van continent Noord-Amerika
	 *
	 * @var string
	 */
	const COLOR_NORTH_AMERICA = '#fbba00';

	/**
	 * PostcodeAPI URL
	 *
	 * @var string
	 */
	const POSTCODE_API_URL = 'https://postcode-api.apiwise.nl/v2/addresses/';

	/**
	 * Spam check API URL
	 *
	 * @var string
	 */
	const SPAM_CHECK_API_URL = 'https://europe.stopforumspam.org/api';

	/**
	 * Plato webservice base-url
	 *
	 * @var string
	 */
	const PLATO_WEBSERVICE_URL = 'https://workcamp-plato.org/files/services/ExternalSynchronization.asmx/';

	/**
	 * BuZa-reisadvies base-url
	 *
	 * @var string
	 */
	const TRAVEL_ADVICE_BASE_URL = 'https://opendata.nederlandwereldwijd.nl/v1/sources/nederlandwereldwijd/infotypes/traveladvice/';

	/**
	 * Wisselkoersen API base-url
	 *
	 * @var string
	 */
	const EXCHANGE_RATES_API_URL = 'https://data.fixer.io/api/latest';

	/**
	 * PHP-DSN voor Sentry
	 *
	 * @var string
	 */
	const SENTRY_PHP_DSN = 'https://d66e53bd9d3e41199ff984851c98706b@sentry.io/1264830';

	/**
	 * JS-DSN voor Sentry
	 *
	 * @var string
	 */
	const SENTRY_JS_DSN = 'https://e8240c08387042d583692b6415c700e3@sentry.io/1264820';
	
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
	static public function get_all() {

		$parser = new AnnotationParser();

		$reflectionClass = new ReflectionClass( __CLASS__ );
		$constants = $reflectionClass->getConstants();
		$configuration = [];
		foreach ( $constants as $name => $value ) {
			$reflectionConstant = new ReflectionClassConstant( __CLASS__, $name );
			$comment = $reflectionConstant->getDocComment();
			$description = $parser->getDescription( $comment );
			$configuration[ $name ] = [ 'name' => $name, 'value' => $value, 'description' => $description ];
		}

		return $configuration;
	}
}
