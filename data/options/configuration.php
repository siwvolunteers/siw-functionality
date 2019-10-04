<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. configuratie
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

//Zoek MailPoet-lijsten
if ( class_exists( 'WYSIJA' ) ) {
	$model_list = WYSIJA::get( 'list','model' );
	$lists = $model_list->get( ['name','list_id' ], ['is_enabled' => 1] );
	foreach ( $lists as $list ) {
		$mailpoet_lists[ $list['list_id'] ] = $list['name'];
	}
}
else {
	$mailpoet_lists = [] ;
}

$data = [
	'id'             => 'configuration',
	'title'          => __( 'Configuratie', 'siw' ),
	'settings_pages' => 'configuration',
	'tabs'           => [
		'analytics'    => __( 'Analytics', 'siw' ),
		'api'          => __( 'API keys', 'siw' ),
		'forms'        => __( 'Formulieren', 'siw' ),
		'newsletter'   => __( 'Nieuwsbrief', 'siw' ),
		'plato'        => __( 'Plato', 'siw' ),
		'verification' => __( 'Website verificatie', 'siw' ),
	],
	'tab_style' => 'left',
	'fields'    => [
		[
			'id'    => 'google_analytics_property_id',
			'name'  => __( 'Google Analytics Property ID', 'siw' ),
			'type'  => 'text',
			'tab'   => 'analytics',
			'size'  => 60,
		],
		[
			'id'                => 'exchange_rates_api_key',
			'name'              => __( 'Wisselkoersen API Key', 'siw' ),
			'type'              => 'text',
			'tab'               => 'api',
			'size'              => 60,
			'label_description' => 'https://fixer.io/',
		],
		[
			'id'                => 'google_maps_api_key',
			'name'              => __( 'Google Maps API Key', 'siw' ),
			'type'              => 'text',
			'tab'               => 'api',
			'size'              => 60,
			'label_description' => 'https://cloud.google.com/maps-platform/maps/',
		],
		[
			'id'                => 'plato_organization_webkey',
			'name'              => __( 'Organization webkey', 'siw' ),
			'type'              => 'text',
			'tab'               => 'plato',
			'size'              => 60,
		],
		[
			'id'                => 'plato_production_mode',
			'name'              => __( 'Productie-mode', 'siw' ),
			'type'              => 'switch',
			'tab'               => 'plato',
			'on_label'          => __( 'Aan', 'siw' ),
			'off_label'         => __( 'Uit', 'siw'),
		],
		[
			'id'                => 'plato_force_full_update',
			'name'              => __( 'Forceer volledige update', 'siw' ),
			'type'              => 'switch',
			'tab'               => 'plato',
			'on_label'          => __( 'Aan', 'siw' ),
			'off_label'         => __( 'Uit', 'siw'),
		],
		[
			'id'      => 'spam_check_mode',
			'name'    => __( 'Spam check mode', 'siw' ),
			'type'    => 'button_group',
			'tab'     => 'forms',
			'options' => [
				'report' => __( 'Rapporteren', 'siw' ),
				'block'  => __( 'Blokkeren', 'siw' ),
			]
		],
		[
			'id'    => 'google_verification',
			'name'  => __( 'Google Search Console', 'siw' ),
			'type'  => 'text',
			'tab'   => 'verification',
			'size'  => 60,
		],
		[
			'id'    => 'bing_verification',
			'name'  => __( 'Bing Webmaster Tools', 'siw' ),
			'type'  => 'text',
			'tab'   => 'verification',
			'size'  => 60,
		],
		[
			'id'      => 'newsletter_list',
			'name'    => __( 'Lijst', 'siw' ),
			'type'    => 'select',
			'tab'     => 'newsletter',
			'options' => $mailpoet_lists,
		],
	],
];

return $data;
