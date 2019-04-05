<?php
/**
 * Metabox voor Op Maat Landen
 * 
 * @package    SIW\CPT
 * @author     Maarten Bruna
 * @copyright  2019 SIW Internationale Vrijwilligersprojecten
 * */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( __DIR__ . "/metabox.php" );

$tmc_cpt = new SIW_CPT( 'tm_country');
$args = [
	'menu_icon' => 'dashicons-location-alt',
];

$labels = [
	'name'               => __( 'Op Maat landen', 'siw' ),
	'singular_name'      => __( 'Op Maat land', 'siw' ),
	'add_new'            => __( 'Nieuw Op Maat land', 'siw' ),
	'add_new_item'       => __( 'Voeg Op Maat land toe', 'siw' ),
	'edit_item'          => __( 'Bewerk Op Maat land', 'siw' ),
	'new_item'           => __( 'Nieuw Op Maat land', 'siw' ),
	'all_items'          => __( 'Alle Op Maat landen', 'siw' ),
	'view_item'          => __( 'Bekijk Op Maat land', 'siw' ),
	'search_items'       => __( 'Zoek Op Maat land', 'siw' ),
	'not_found'          => __( 'Geen Op Maat landen gevonden', 'siw' ),
	'not_found_in_trash' => __( 'Geen Op Maat landen gevonden in de prullenbak', 'siw' ),
	'archives'           => __( 'Alle Op Maat landen', 'siw' ),
];

$taxonomy_args =[];

$taxonomy_labels = array(
	'name'                       => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
	'singular_name'              => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
	'menu_name'                  => __( 'Continenten', 'siw' ),
	'all_items'                  => __( 'Alle continenten', 'siw' ),
	'new_item_name'              => __( 'New Item Name', 'siw' ),
	'add_new_item'               => __( 'Continent toevoegen', 'siw' ),
	'edit_item'                  => __( 'Edit Item', 'siw' ),
	'update_item'                => __( 'Continent bijwerken', 'siw' ),
	'view_item'                  => __( 'View Item', 'siw' ),
	'search_items'               => __( 'Search Items', 'siw' ),
	'not_found'                  => __( 'Geen continenten gevonden', 'siw' ),
	'no_terms'                   => __( 'No items', 'siw' ),
	'items_list'                 => __( 'Items list', 'siw' ),
	'items_list_navigation'      => __( 'Items list navigation', 'siw' ),
);

$tmc_cpt->register_taxonomy( 'continent', $taxonomy_labels, $taxonomy_args, 'vrijwilligerswerk-op-maat' );

$tmc_cpt->register( $args, $labels, 'vrijwilligerswerk-op-maat-in', 'vrijwilligerswerk-op-maat');
