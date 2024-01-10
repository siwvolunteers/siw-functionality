<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use Spatie\Enum\Enum;

/**
 * WooCommerce taxonomy attributes
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self CONTINENT()
 * @method static self COUNTRY()
 * @method static self WORK_TYPE()
 * @method static self LANGUAGE()
 * @method static self TARGET_AUDIENCE()
 * @method static self SDG()
 * @method static self MONTH()
 */
class Taxonomy_Attribute extends Enum {

	/** {@inheritDoc} */
	protected static function values(): array {
		return [
			'CONTINENT'       => 'product_cat',
			'COUNTRY'         => 'pa_land',
			'WORK_TYPE'       => 'pa_soort-werk',
			'LANGUAGE'        => 'pa_taal',
			'TARGET_AUDIENCE' => 'pa_doelgroep',
			'SDG'             => 'pa_sdg',
			'MONTH'           => 'pa_maand',
		];
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'CONTINENT'       => __( 'Continent', 'siw' ),
			'COUNTRY'         => __( 'Land', 'siw' ),
			'WORK_TYPE'       => __( 'Soort werk', 'siw' ),
			'LANGUAGE'        => __( 'Taal', 'siw' ),
			'TARGET_AUDIENCE' => __( 'Doelgroep', 'siw' ),
			'SDG'             => __( 'Sustainable Development Goal', 'siw' ),
			'MONTH'           => __( 'Maand', 'siw' ),
		];
	}
}
