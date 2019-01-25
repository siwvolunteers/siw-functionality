<?php
/*
 * (c)2017-2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {
	$labels = [
		'name'               => _x( 'EVS-project', 'Post Type General Name', 'siw' ),
		'singular_name'      => _x( 'EVS-project', 'Post Type Singular Name', 'siw' ),
		'menu_name'          => __( 'EVS-projecten', 'siw' ),
		'name_admin_bar'     => __( 'EVS-project', 'siw' ),
		'parent_item_colon'  => __( 'Parent Item:', 'siw' ),
		'all_items'          => __( 'Alle projecten', 'siw' ),
		'add_new_item'       => __( 'EVS-project toevoegen', 'siw' ),
		'add_new'            => __( 'Toevoegen', 'siw' ),
		'new_item'           => __( 'Nieuw EVS-project', 'siw' ),
		'edit_item'          => __( 'Bewerk EVS-project', 'siw' ),
		'update_item'        => __( 'Update EVS-project', 'siw' ),
		'view_item'          => __( 'Bekijk EVS-project', 'siw' ),
		'search_items'       => __( 'Zoek EVS-project', 'siw' ),
		'not_found'          => __( 'Niet gevonden', 'siw' ),
		'not_found_in_trash' => __( 'Niet gevonden in de prullenbak', 'siw' ),
	];
	$args = [
		'label'               => __( 'EVS-project', 'siw' ),
		'description'         => __( 'EVS-project', 'siw' ),
		'labels'              => $labels,
		'supports'            => [ 'title', 'excerpt', 'thumbnail', 'revisions' ],
		//'taxonomies'        => array( 'agenda_type' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar-alt',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => [ 'slug' => 'evs-projecten' ],
		'capability_type'     => 'evs_project',
		'map_meta_cap'        => true,
	];
	register_post_type( 'evs_project', $args );
}, 0 );

add_filter( 'single_template', function( $template, $type, $templates ) {

	if ( in_array( 'single-evs_project', $templates ) ) {
		$template = SIW_TEMPLATES_DIR . '/single-evs_project';
	}

	return $template;
}, 10, 3 );



add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {
	$prefix = 'siw_evs_project_';

	$meta_boxes[] = [
		'id'         => 'siw_evs_project_',
		'title'      => __( 'EVS-project', 'siw' ),
		'post_types' => 'evs_project',
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => [
			[
				'id'       => $prefix . 'land',
				'name'     => __( 'Land', 'siw' ),
				'type'     => 'select',
				'options'  => siw_get_evs_countries(),
				'required' => true,
			],
			[
				'id'       => $prefix . 'plaats',
				'name'     => __( 'Plaats', 'siw' ),
				'type'     => 'text',
				'required' => true,
			],
			[
				'id'        => $prefix . 'soort_werk',
				'name'      => __( 'Soort werk', 'siw' ),
				'type'      => 'radio',
				'inline'    => false,
				'options'   => siw_get_evs_project_work_types(),
				'required'  => true,
			],
			[
				'id'         => $prefix . 'startdatum',
				'name'       => __( 'Startdatum', 'siw' ),
				'type'       => 'date',
				'required'   => true,
				'timestamp'  => true,
				'readonly'   => true,
				'js_options' => [
					'dateFormat' => 'd MM yy',
					'controlType' => 'select',
				],
			],
			[
				'id'         => $prefix . 'einddatum',
				'name'       => __( 'Einddatum', 'siw' ),
				'type'       => 'date',
				'required'   => true,
				'timestamp'  => true,
				'readonly'   => true,
				'js_options' => [
					'dateFormat' => 'd MM yy',
					//'controlType' => 'select',
				],
			],
			[
				'id'       => $prefix . 'highlight_quote',
				'name'     => __( 'Quote', 'siw' ),
				'type'     => 'text',
				'size'     => 100,
			],

			[
				'id'        => $prefix . 'deadline',
				'name'      => __( 'Deadline', 'siw' ),
				'type'      => 'date',
				'required'  => true,
				'timestamp' => true,
				'readonly'  => true,
				'js_options' => [
					'dateFormat' => 'd MM yy',
					'controlType' => 'select',
				],
			],
			[
				'id'       => $prefix . 'wat_ga_je_doen',
				'name'     => __( 'Wat ga je doen?', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
				'raw'      => true,
				'options'  => [
					'teeny'         => true,
					'media_buttons' => false,
					'teeny'         => true,
					'textarea_rows' => 5,
				],
			],
			[
				'id'       => $prefix . 'organisatie',
				'name'     => __( 'Bij welke organisatie ga je werken?', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
				'raw'      => true,
				'options'  => [
					'teeny'         => true,
					'media_buttons' => false,
					'teeny'         => true,
					'textarea_rows' => 5,
				],
			],
		],
	];
	return $meta_boxes;
});
