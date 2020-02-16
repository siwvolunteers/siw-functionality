<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. API's
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'api',
	'title'          => __( 'API', 'siw' ),
	'settings_pages' => 'configuration',
	'tab'            => 'api',
	'fields'         => [
		[
			'type'      => 'heading',
			'name'      => __( 'Google Analytics', 'siw' ),
		],
		[
			'id'        => 'google_analytics_property_id',
			'name'      => __( 'Property ID', 'siw' ),
			'type'      => 'text',
			'size'      => 60,
		],
		[
			'type'      => 'heading',
			'name'      => __( 'Google Maps', 'siw' ),
		],
		[
			'id'        => 'google_maps_api_key',
			'name'      => __( 'API Key', 'siw' ),
			'type'      => 'text',
			'size'      => 60,
		],
		[
			'type'      => 'heading',
			'name'      => 'fixer.io',
			'desc'      => __( 'Wisselkoersen', 'siw' ),
		],
		[
			'id'        => 'exchange_rates_api_key',
			'name'      => __( 'API Key', 'siw' ),
			'type'      => 'text',
			'size'      => 60,
		],
		[
			'type'      => 'heading',
			'name'      => __( 'Mailjet', 'siw' ),
		],
		[
			'id'        => 'mailjet_api_key',
			'name'      => __( 'API Key', 'siw' ),
			'type'      => 'text',
			'size'      => 60,
		],
		[
			'id'        => 'mailjet_secret_key',
			'name'      => __( 'Secret Key', 'siw' ),
			'type'      => 'text',
			'size'      => 60,
		],
		[
			'type'      => 'heading',
			'name'      => __( 'Plato', 'siw' ),
		],
		[
			'id'        => 'plato_organization_webkey',
			'name'      => __( 'Organization webkey', 'siw' ),
			'type'      => 'text',
			'size'      => 60,
		],
		[
			'id'        => 'plato_production_mode',
			'name'      => __( 'Productie-mode', 'siw' ),
			'type'      => 'switch',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
		],
		[
			'id'        => 'plato_force_full_update',
			'name'      => __( 'Forceer volledige update', 'siw' ),
			'type'      => 'switch',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
		],
	],
];

return $data;
