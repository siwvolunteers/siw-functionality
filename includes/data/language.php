<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Language: string implements Labels {
	use Enum_List;

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

	#[\Override]
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
}
