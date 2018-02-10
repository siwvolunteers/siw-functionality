<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Custom post type Agenda registreren */
add_action( 'init', function() {
	$labels = array(
		'name'					=> _x( 'Agenda', 'Post Type General Name', 'siw' ),
		'singular_name'			=> _x( 'Agenda', 'Post Type Singular Name', 'siw' ),
		'menu_name'				=> __( 'Agenda', 'siw' ),
		'name_admin_bar'		=> __( 'Agenda', 'siw' ),
		'parent_item_colon'		=> __( 'Parent Item:', 'siw' ),
		'all_items'				=> __( 'Alle evenementen', 'siw' ),
		'add_new_item'			=> __( 'Evenement toevoegen', 'siw' ),
		'add_new'				=> __( 'Toevoegen', 'siw' ),
		'new_item'				=> __( 'Nieuw evenement', 'siw' ),
		'edit_item'				=> __( 'Bewerk evenement', 'siw' ),
		'update_item'			=> __( 'Update evenement', 'siw' ),
		'view_item'				=> __( 'Bekijk evenement', 'siw' ),
		'search_items'			=> __( 'Zoek evenement', 'siw' ),
		'not_found'				=> __( 'Niet gevonden', 'siw' ),
		'not_found_in_trash'	=> __( 'Niet gevonden in de prullenbak', 'siw' ),
	);
	$args = array(
		'label'					=> __( 'Evenement', 'siw' ),
		'description'			=> __( 'Evenement', 'siw' ),
		'labels'				=> $labels,
		'supports'				=> array( 'title', 'excerpt', 'revisions'),
		'taxonomies'			=> array( 'agenda_type' ),
		'hierarchical'			=> false,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'menu_position'			=> 5,
		'menu_icon'				=> 'dashicons-calendar-alt',
		'show_in_admin_bar'		=> true,
		'show_in_nav_menus'		=> true,
		'can_export'			=> true,
		'has_archive'			=> false,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'capability_type'		=> 'event',
		'map_meta_cap'			=> true,
	);
	register_post_type( 'agenda', $args );
}, 0 );


/*  Taxonomy 'Soort evenemnt' voor cpt Agenda registreren */
add_action( 'init', function() {
	$labels = array(
		'name'							=> _x( 'Soort evenement', 'Taxonomy General Name', 'siw' ),
		'singular_name'					=> _x( 'Taxonomy', 'Taxonomy Singular Name', 'siw' ),
		'menu_name'						=> __( 'Soort evement', 'siw' ),
		'all_items'						=> __( 'All Items', 'siw' ),
		'parent_item'					=> __( 'Parent Item', 'siw' ),
		'parent_item_colon'				=> __( 'Parent Item:', 'siw' ),
		'new_item_name'					=> __( 'New Item Name', 'siw' ),
		'add_new_item'					=> __( 'Add New Item', 'siw' ),
		'edit_item'						=> __( 'Edit Item', 'siw' ),
		'update_item'					=> __( 'Update Item', 'siw' ),
		'view_item'						=> __( 'View Item', 'siw' ),
		'separate_items_with_commas'	=> __( 'Separate items with commas', 'siw' ),
		'add_or_remove_items'			=> __( 'Add or remove items', 'siw' ),
		'choose_from_most_used'			=> __( 'Choose from the most used', 'siw' ),
		'popular_items'					=> __( 'Popular Items', 'siw' ),
		'search_items'					=> __( 'Search Items', 'siw' ),
		'not_found'						=> __( 'Not Found', 'siw' ),
	);
	$args = array(
		'labels'						=> $labels,
		'hierarchical'					=> true,
		'public'						=> true,
		'show_ui'						=> true,
		'show_admin_column'				=> true,
		'show_in_nav_menus'				=> true,
		'query_var'						=> true,
		'capabilities' => array(
			'assign_terms' => 'edit_events'
		),
	);
	register_taxonomy( 'soort_evenement', array( 'agenda' ), $args );

}, 0 );
