<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */


add_filter( 'siw_settings_pages', function( $pages ) {

	$pages[] = [
		'id'            => 'siw-options-workcamps',
		'menu_title'    => __( 'Groepsprojecten', 'siw' ),
		'capability'    => 'manage_options',
	];

	return $pages;
} );

add_filter( 'siw_settings_meta_boxes', function( $boxes ) {

	$boxes[] = [
		'id'             => 'email',
		'title'          => __( 'Groepsprojecten', 'siw' ),
		'settings_pages' => 'siw-options-workcamps',
		'tabs' =>[
			'sale'        => __( 'Kortingsactie', 'siw' ),
			'archive'     => __( 'Overzichtspagina', 'siw' ),
			'plato'       => __( 'Plato', 'siw' ),
		],
		'tab_style' => 'left',
		'fields' => [
			[
				'id'                => 'workcamp_sale_active',
				'name'              => __( 'Kortingsactie actief', 'siw' ),
				'type'              => 'switch',
				'on_label'          => __( 'Ja', 'siw' ),
				'off_label'         => __( 'Nee', 'siw'),
				'tab'               => 'sale',
			],
			[
				'id'                => 'workcamp_sale_start_date',
				'name'              => __( 'Startdatum kortingsactie', 'siw' ),
				'type'              => 'date',
				'tab'               => 'sale',
				'visible'           => [ 'workcamp_sale_active', true ],
			],
			[
				'id'                => 'workcamp_sale_end_date',
				'name'              => __( 'Einddatum kortingsactie', 'siw' ),
				'type'              => 'date',
				'tab'               => 'sale',
				'visible'           => [ 'workcamp_sale_active', true ],
			],
			[
				'type'              => 'custom_html',
				'tab'               => 'sale',
				'visible'           => [ 'workcamp_sale_active', true ],
				'std'               => sprintf( 'Regulier: %s, Student: %s', SIW_Properties::WORKCAMP_FEE_REGULAR_SALE, SIW_Properties::WORKCAMP_FEE_STUDENT_SALE ),
				//TODO: netter + i18n
			],
			[
				'id'                => 'workcamp_teaser_text_enabled',
				'name'              => __( 'Aankondiging tonen', 'siw' ),
				'type'              => 'switch',
				'on_label'          => __( 'Ja', 'siw' ),
				'off_label'         => __( 'Nee', 'siw'),
				'tab'               => 'archive',
			],
			[
				'id'                => 'workcamp_teaser_text_start_date',
				'name'              => __( 'Startdatum aankonding', 'siw' ),
				'type'              => 'date',
				'tab'               => 'archive',
				'visible'           => [ 'workcamp_teaser_text_enabled', true ],
			],
			[
				'id'                => 'workcamp_teaser_text_end_date',
				'name'              => __( 'Einddatum aankonding', 'siw' ),
				'type'              => 'date',
				'tab'               => 'archive',
				'visible'           => [ 'workcamp_teaser_text_enabled', true ],
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Outgoing Placements', 'siw' ),
				'desc'              => __( 'Afzender van de aanmelding bij export naar Plato', 'siw' ),
				'tab'               => 'plato',
			],
			[
				'id'                => 'plato_export_outgoing_placements_name',
				'name'              => __( 'Naam', 'siw' ),
				'type'              => 'text',
				'tab'               => 'plato',
			],
			[
				'id'                => 'plato_export_outgoing_placements_email',
				'name'              => __( 'E-mail', 'siw' ),
				'type'              => 'email',
				'tab'               => 'plato',
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Coördinatoren voor Plato-import', 'siw' ),
				'desc'              => __( 'Staan op cc van mails naar regiospecialisten en ontvangt niet-toegewezen projecten.', 'siw' ),
				'tab'               => 'plato',
			],
			[
				'id'                => 'plato_import_supervisor',
				'name'              => __( 'E-mail', 'siw' ),
				'type'              => 'user',
				'field_type'        => 'select_advanced',
				'tab'               => 'plato',
				'clone'             => 'true',
			],


		],
	];
	return $boxes;
});
