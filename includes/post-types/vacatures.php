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
		'name'					=> _x( 'Vacatures', 'Post Type General Name', 'siw' ),
		'singular_name'			=> _x( 'Vacature', 'Post Type Singular Name', 'siw' ),
		'menu_name'				=> __( 'Vacatures', 'siw' ),
		'name_admin_bar'		=> __( 'Vacature', 'siw' ),
		'parent_item_colon'		=> __( 'Parent Item:', 'siw' ),
		'all_items'				=> __( 'Alle vacatures', 'siw' ),
		'add_new_item'			=> __( 'Vacature toevoegen', 'siw' ),
		'add_new'				=> __( 'Toevoegen', 'siw' ),
		'new_item'				=> __( 'Nieuwe vacature', 'siw' ),
		'edit_item'				=> __( 'Bewerk vacature', 'siw' ),
		'update_item'			=> __( 'Update vacature', 'siw' ),
		'view_item'				=> __( 'Bekijk vacature', 'siw' ),
		'search_items'			=> __( 'Zoek vacature', 'siw' ),
		'not_found'				=> __( 'Niet gevonden', 'siw' ),
		'not_found_in_trash'	=> __( 'Niet gevonden in de prullenbak', 'siw' ),
	);
	$args = array(
		'label'					=> __( 'Vacature', 'siw' ),
		'description'			=> __( 'Vacatures', 'siw' ),
		'labels'				=> $labels,
		'supports'				=> array( 'title', 'excerpt', 'revisions' ),
		'taxonomies'			=> array( 'job_type' ),
		'hierarchical'			=> false,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'menu_position'			=> 5,
		'menu_icon'				=> 'dashicons-nametag',
		'show_in_admin_bar'		=> true,
		'show_in_nav_menus'		=> true,
		'can_export'			=> true,
		'has_archive'			=> false,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'capability_type'		=> 'job',
		'map_meta_cap'			=> true,
	);
	register_post_type( 'vacatures', $args );
}, 0 );


/*  Taxonomy 'Soort vacature' voor cpt Vacatures registreren */
add_action( 'init', function() {
	$labels = array(
		'name'							=> _x( 'Soort vacature', 'Taxonomy General Name', 'siw' ),
		'singular_name'					=> _x( 'Taxonomy', 'Taxonomy Singular Name', 'siw' ),
		'menu_name'						=> __( 'Soort vacature', 'siw' ),
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
		'labels'			=> $labels,
		'hierarchical'		=> true,
		'public'			=> true,
		'show_ui'			=> true,
		'show_admin_column'	=> true,
		'show_in_nav_menus'	=> true,
		'query_var'			=> true,
		'capabilities' => array(
			'assign_terms' => 'edit_jobs'
		),
	);
	register_taxonomy( 'soort_vacature', array( 'vacatures' ), $args );
}, 0 );
