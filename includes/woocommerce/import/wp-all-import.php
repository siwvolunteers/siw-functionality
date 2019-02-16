<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Voeg metabox met projectgegevens toe
 */
$siw_wpai_addon = new RapidAddon(
	__( 'Extra gegevens groepsproject', 'siw' ),
	'siw_wpai_addon'
);

$siw_wpai_addon->add_title(
	__( 'Projecteigenschappen', 'siw' )
);
$siw_wpai_addon->add_field(
	'project_id',
	__( 'Project ID', 'siw' ),
	'text'
);
$siw_wpai_addon->add_field(
	'startdatum',
	__( 'Startdatum', 'siw' ),
	'text',
	null,
	__( 'jjjj-mm-dd', 'siw' )
);
$siw_wpai_addon->add_field(
	'land',
	__( 'Land', 'siw' ),
	'text',
	null,
	__( 'Naam van het land (t.b.v. sorteerfunctie)', 'siw' )
);
$siw_wpai_addon->add_field(
	'minimumleeftijd',
	__( 'Minimumleeftijd', 'siw' ),
	'text',
	null,
	__( 'Wordt gebruikt bij validaties.', 'siw' )
);
$siw_wpai_addon->add_field(
	'allowed',
	__( 'Toegestaan', 'siw' ),
	'text',
	null,
	__( 'Geef aan of dit een project in een toegestaan land is (yes/no)', 'siw' )
);
$siw_wpai_addon->add_field(
	'default_tariff',
	__( 'Standaard tarief', 'siw' ),
	'radio',
	array(
		'student' => __( 'Student/<18', 'siw' ),
 		'regulier' => __( 'Regulier', 'siw' ),
	)
);
$siw_wpai_addon->add_title(
	__( 'Projectlocatie', 'siw' ),
	__( 'Geef de coördinaten van het project op', 'siw' )
);
$siw_wpai_addon->add_field(
	'latitude',
	__( 'Latitude', 'siw' ),
	'text'
);
$siw_wpai_addon->add_field(
	'longitude',
	__( 'Longitude', 'siw' ),
	'text'
);
$siw_wpai_addon->add_title(
	__( 'Lokale bijdrage', 'siw' )
);
$siw_wpai_addon->add_field(
	'participation_fee_currency',
	__( 'Valuta', 'siw' ),
	'text'
);
$siw_wpai_addon->add_field(
	'participation_fee',
	__( 'Bedrag', 'siw' ),
	'text'
);
$siw_wpai_addon->add_title(
	__( 'SEO', 'siw' )
);
$siw_wpai_addon->add_field(
	'_genesis_title',
	__( 'SEO titel', 'siw' ),
	'text'
);
$siw_wpai_addon->add_field(
	'_genesis_description',
	__( 'Meta omschrijving', 'siw' ),
	'textarea'
);

$siw_wpai_addon->set_import_function( 'siw_wpai_addon_import' );
$siw_wpai_addon->run(
	array(
		'post_types' => array( 'product' ),
	)
);


/**
 * Verwerk extra custom fields
 * @param  int $post_id
 * @param  array $data
 * @param  array $import_options
 *
 * @return void
 */
function siw_wpai_addon_import( $post_id, $data, $import_options ) {
	global $siw_wpai_addon;
	if ( $siw_wpai_addon->can_update_meta( 'project_id', $import_options ) ) {
		update_post_meta( $post_id, 'project_id', $data['project_id'] );
	}
	if ( $siw_wpai_addon->can_update_meta( 'startdatum', $import_options ) ) {
		update_post_meta( $post_id, 'startdatum', $data['startdatum'] );
	}
	if ( $siw_wpai_addon->can_update_meta( 'land', $import_options ) ) {
		update_post_meta( $post_id, 'land', $data['land']);
	}
	if ( $siw_wpai_addon->can_update_meta( 'minimumleeftijd', $import_options ) ) {
		update_post_meta( $post_id, 'minimumleeftijd', $data['minimumleeftijd'] );
	}
	if ( $siw_wpai_addon->can_update_meta( 'allowed', $import_options ) ) {
		update_post_meta( $post_id, 'allowed', $data['allowed'] );
	}
	if ( $siw_wpai_addon->can_update_meta( '_default_attributes', $import_options ) ) {
		$default_attributes = array( 'pa_tarief' => $data['default_tariff'] );
		update_post_meta( $post_id, '_default_attributes', $default_attributes );
	}
	if ( $siw_wpai_addon->can_update_meta( 'latitude', $import_options ) ) {
		update_post_meta( $post_id, 'latitude', $data['latitude'] );
	}
	if ( $siw_wpai_addon->can_update_meta( 'longitude', $import_options ) ) {
		update_post_meta( $post_id, 'longitude', $data['longitude'] );
	}
	if ( $siw_wpai_addon->can_update_meta( 'participation_fee_currency', $import_options ) ) {
		update_post_meta( $post_id, 'participation_fee_currency', $data['participation_fee_currency'] );
	}
	if ( $siw_wpai_addon->can_update_meta( 'participation_fee', $import_options ) ) {
		update_post_meta( $post_id, 'participation_fee', $data['participation_fee'] );
	}
	if ( $siw_wpai_addon->can_update_meta( '_genesis_title', $import_options ) ) {
		SIW_Util::set_seo_title( $post_id, $data['_genesis_title'] );
	}
	if ( $siw_wpai_addon->can_update_meta( '_genesis_description', $import_options ) ) {
		SIW_Util::set_seo_description( $post_id, $data['_genesis_description'] );
	}
}
