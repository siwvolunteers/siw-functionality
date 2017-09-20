<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {
	$labels = array(
		'name'					=> _x( 'EVS-project', 'Post Type General Name', 'siw' ),
		'singular_name'			=> _x( 'EVS-project', 'Post Type Singular Name', 'siw' ),
		'menu_name'				=> __( 'EVS-projecten', 'siw' ),
		'name_admin_bar'		=> __( 'EVS-project', 'siw' ),
		'parent_item_colon'		=> __( 'Parent Item:', 'siw' ),
		'all_items'				=> __( 'Alle projecten', 'siw' ),
		'add_new_item'			=> __( 'EVS-project toevoegen', 'siw' ),
		'add_new'				=> __( 'Toevoegen', 'siw' ),
		'new_item'				=> __( 'Nieuw EVS-project', 'siw' ),
		'edit_item'				=> __( 'Bewerk EVS-project', 'siw' ),
		'update_item'			=> __( 'Update EVS-project', 'siw' ),
		'view_item'				=> __( 'Bekijk EVS-project', 'siw' ),
		'search_items'			=> __( 'Zoek EVS-project', 'siw' ),
		'not_found'				=> __( 'Niet gevonden', 'siw' ),
		'not_found_in_trash'	=> __( 'Niet gevonden in de prullenbak', 'siw' ),
	);
	$args = array(
		'label'					=> __( 'EVS-project', 'siw' ),
		'description'			=> __( 'EVS-project', 'siw' ),
		'labels'				=> $labels,
		'supports'				=> array( 'title', 'excerpt', 'thumbnail', 'revisions' ),
		//'taxonomies'			=> array( 'agenda_type' ),
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
		'rewrite' 				=> array( 'slug' => 'evs-projecten' ),
		'capability_type'		=> 'evs_project',
		'map_meta_cap'			=> true,
	);
	register_post_type( 'evs_project', $args );
}, 0 );
