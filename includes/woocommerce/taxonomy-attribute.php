<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Interfaces\Enums\Labels as I_Enum_Labels;

/**
 * WooCommerce taxonomy attributes
 *
 * @copyright 2021-2023 SIW Internationale Vrijwilligersprojecten
 */
enum Taxonomy_Attribute: string implements I_Enum_Labels {

	case CONTINENT       = 'product_cat';
	case COUNTRY         = 'pa_land';
	case WORK_TYPE       = 'pa_soort-werk';
	case LANGUAGE        = 'pa_taal';
	case TARGET_AUDIENCE = 'pa_doelgroep';
	case SDG             = 'pa_sdg';
	case MONTH           = 'pa_maand';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
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
