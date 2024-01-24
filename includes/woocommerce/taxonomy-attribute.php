<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Interfaces\Enums\Labels as I_Enum_Labels;

enum Taxonomy_Attribute: string implements I_Enum_Labels {

	case PROJECT_TYPE    = 'product_cat';
	case CONTINENT       = 'pa_continent';
	case COUNTRY         = 'pa_land';
	case WORK_TYPE       = 'pa_soort-werk';
	case LANGUAGE        = 'pa_taal';
	case TARGET_AUDIENCE = 'pa_doelgroep';
	case SDG             = 'pa_sdg';
	case MONTH           = 'pa_maand';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::PROJECT_TYPE    => __( 'Projecttype', 'siw' ),
			self::CONTINENT       => __( 'Continent', 'siw' ),
			self::COUNTRY         => __( 'Land', 'siw' ),
			self::WORK_TYPE       => __( 'Soort werk', 'siw' ),
			self::LANGUAGE        => __( 'Taal', 'siw' ),
			self::TARGET_AUDIENCE => __( 'Doelgroep', 'siw' ),
			self::SDG             => __( 'Sustainable Development Goal', 'siw' ),
			self::MONTH           => __( 'Maand', 'siw' ),
		};
	}
}
