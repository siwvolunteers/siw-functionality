<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Noord-Amerika
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
$siw_data = [
	[
		'plato_code'  => 'CAN',
		'iso_code'    => 'ca',
		'slug'        => 'canada',
		'name'        => __( 'Canada', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'GRL',
		'iso_code'    => 'gl',
		'slug'        => 'groenland',
		'name'        => __( 'Greenland', 'woocommerce' ),
		'continent'   => 'noord-amerika',
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'USA',
		'iso_code'    => 'us',
		'slug'        => 'verenigde-staten',
		'name'        => __( 'United States (US)', 'woocommerce' ),
		'continent'   => 'noord-amerika',
		'workcamps'   => true,
		'tailor_made' => false,
	],
];
// phpcs:enable WordPress.WP.I18n.TextDomainMismatch
return $siw_data;
