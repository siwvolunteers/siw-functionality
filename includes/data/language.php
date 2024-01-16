<?php declare(strict_types=1);
// phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;
use SIW\Interfaces\Enums\Plato_Code;
use SIW\Traits\Enum_List;
use SIW\Traits\Plato_Enum;

enum Language: string implements Labels, Plato_Code {
	use Enum_List;
	use Plato_Enum;

	case ARABIC = 'arabisch';
	case CATALAN = 'catalaans';
	case CHINESE = 'chinees';
	case DANISH = 'deens';
	case GERMAN = 'duits';
	case ENGLISH = 'engels';
	case ESTONIAN = 'estisch';
	case FINNISH = 'fins';
	case FRENCH = 'frans';
	case GREEK = 'grieks';
	case HEBREW = 'hebreeuws';
	case ITALIAN = 'italiaans';
	case JAPANESE = 'japans';
	case KOREAN = 'koreaans';
	case DUTCH = 'nederlands';
	case UKRAINIAN = 'oekraiens';
	case POLISH = 'pools';
	case PORTUGUESE = 'portugees';
	case RUSSIAN = 'russisch';
	case SLOWAK = 'slowaaks';
	case SPANISH = 'spaans';
	case CZECH = 'tsjechisch';
	case TURKISH = 'turks';
	case SWEDISH = 'zweeds';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::ARABIC     => __( 'Arabisch', 'siw' ),
			self::CATALAN    => __( 'Catalaans', 'siw' ),
			self::CHINESE    => __( 'Chinees', 'siw' ),
			self::DANISH     => __( 'Deens', 'siw' ),
			self::GERMAN     => __( 'Duits', 'siw' ),
			self::ENGLISH    => __( 'Engels', 'siw' ),
			self::ESTONIAN   => __( 'Estisch', 'siw' ),
			self::FINNISH    => __( 'Fins', 'siw' ),
			self::FRENCH     => __( 'Frans', 'siw' ),
			self::GREEK      => __( 'Grieks', 'siw' ),
			self::HEBREW     => __( 'Hebreeuws', 'siw' ),
			self::ITALIAN    => __( 'Italiaans', 'siw' ),
			self::JAPANESE   => __( 'Japans', 'siw' ),
			self::KOREAN     => __( 'Koreaans', 'siw' ),
			self::DUTCH      => __( 'Nederlands', 'siw' ),
			self::UKRAINIAN  => __( 'Oekraïens', 'siw' ),
			self::POLISH     => __( 'Pools', 'siw' ),
			self::PORTUGUESE => __( 'Portugees', 'siw' ),
			self::RUSSIAN    => __( 'Russisch', 'siw' ),
			self::SLOWAK     => __( 'Slowaaks', 'siw' ),
			self::SPANISH    =>__( 'Spaans', 'siw' ),
			self::CZECH      =>__( 'Tsjechisch', 'siw' ),
			self::TURKISH    => __( 'Turks', 'siw' ),
			self::SWEDISH    => __( 'Zweeds', 'siw' ),
		};
	}

	/** {@inheritDoc} */
	public function plato_code(): string {
		return match ( $this ) {
			self::ARABIC     => 'ARA',
			self::CATALAN    => 'CAT',
			self::CHINESE    => 'CHN',
			self::DANISH     => 'DNK',
			self::GERMAN     => 'GER',
			self::ENGLISH    => 'ENG',
			self::ESTONIAN   => 'EST',
			self::FINNISH    => 'FIN',
			self::FRENCH     => 'FRA',
			self::GREEK      => 'GRE',
			self::HEBREW     => 'HEB',
			self::ITALIAN    => 'ITA',
			self::JAPANESE   => 'JAP',
			self::KOREAN     => 'KOR',
			self::DUTCH      => 'HOL',
			self::UKRAINIAN  => 'UKR',
			self::POLISH     => 'POL',
			self::PORTUGUESE => 'POR',
			self::RUSSIAN    => 'RUS',
			self::SLOWAK     => 'SLK',
			self::SPANISH    => 'ESP',
			self::CZECH      => 'CZE',
			self::TURKISH    => 'TUR',
			self::SWEDISH    => 'SWE',
		};
	}
}
