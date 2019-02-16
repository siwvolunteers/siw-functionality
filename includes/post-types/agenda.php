<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Custom post type Agenda registreren */
add_action( 'init', function() {
	$labels = [
		'name'               => _x( 'Agenda', 'Post Type General Name', 'siw' ),
		'singular_name'      => _x( 'Agenda', 'Post Type Singular Name', 'siw' ),
		'menu_name'          => __( 'Agenda', 'siw' ),
		'name_admin_bar'     => __( 'Agenda', 'siw' ),
		'parent_item_colon'  => __( 'Parent Item:', 'siw' ),
		'all_items'          => __( 'Alle evenementen', 'siw' ),
		'add_new_item'       => __( 'Evenement toevoegen', 'siw' ),
		'add_new'            => __( 'Toevoegen', 'siw' ),
		'new_item'           => __( 'Nieuw evenement', 'siw' ),
		'edit_item'          => __( 'Bewerk evenement', 'siw' ),
		'update_item'        => __( 'Update evenement', 'siw' ),
		'view_item'          => __( 'Bekijk evenement', 'siw' ),
		'search_items'       => __( 'Zoek evenement', 'siw' ),
		'not_found'          => __( 'Niet gevonden', 'siw' ),
		'not_found_in_trash' => __( 'Niet gevonden in de prullenbak', 'siw' ),
	];
	$args = [
		'label'               => __( 'Evenement', 'siw' ),
		'description'         => __( 'Evenement', 'siw' ),
		'labels'              => $labels,
		'supports'            => [ 'title', 'excerpt', 'revisions' ],
		'taxonomies'          => [ 'agenda_type' ],
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
		'capability_type'     => 'event',
		'map_meta_cap'        => true,
	];
	register_post_type( 'agenda', $args );
}, 0 );


/*  Taxonomy 'Soort evenement' voor cpt Agenda registreren */
add_action( 'init', function() {
	$labels = [
		'name'                       => _x( 'Soort evenement', 'Taxonomy General Name', 'siw' ),
		'singular_name'              => _x( 'Taxonomy', 'Taxonomy Singular Name', 'siw' ),
		'menu_name'                  => __( 'Soort evement', 'siw' ),
		'all_items'                  => __( 'All Items', 'siw' ),
		'parent_item'                => __( 'Parent Item', 'siw' ),
		'parent_item_colon'          => __( 'Parent Item:', 'siw' ),
		'new_item_name'              => __( 'New Item Name', 'siw' ),
		'add_new_item'               => __( 'Add New Item', 'siw' ),
		'edit_item'                  => __( 'Edit Item', 'siw' ),
		'update_item'                => __( 'Update Item', 'siw' ),
		'view_item'                  => __( 'View Item', 'siw' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'siw' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'siw' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'siw' ),
		'popular_items'              => __( 'Popular Items', 'siw' ),
		'search_items'               => __( 'Search Items', 'siw' ),
		'not_found'                  => __( 'Not Found', 'siw' ),
	];
	$args = [
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'capabilities' => [
			'assign_terms' => 'edit_events'
		],
	];
	register_taxonomy( 'soort_evenement', ['agenda' ], $args );

}, 0 );

add_filter( 'single_template', function( $template, $type, $templates ) {
	if ( in_array( 'single-agenda.php', $templates ) ) {
		$template = SIW_TEMPLATES_DIR . '/single-agenda.php';
	}
	return $template;
}, 10, 3 );

add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {
	$prefix = 'siw_agenda_';

	$meta_boxes[] = [
		'id'         => 'siw_agenda_meta',
		'title'      => __( 'Agenda', 'siw' ),
		'post_types' => 'agenda',
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => [
			[
				'id'       => $prefix . 'beschrijving',
				'name'     => __( 'Beschrijving', 'siw' ),
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
				'id'       => $prefix . 'highlight_quote',
				'name'     => __( 'Quote', 'siw' ),
				'type'     => 'text',
				'size'     => 100,
			],
			[
				'name'     => __( 'Tijden', 'siw' ),
				'type'     => 'heading',
			],
			[
				'id'       => $prefix . 'start',
				'name'     => __( 'Start', 'siw' ),
				'type'     => 'datetime',
				'required' => true,
				'timestamp' => true,
				'readonly' => true,
				'js_options' => [
					'dateFormat'  => 'd MM yy',
					'stepMinute'  => 15,
					'controlType' => 'select',
				],
				'admin_columns' => [
					'position' => 'after title',
					'sort'     => true,
				],
			],
			[
				'id'       => $prefix . 'eind',
				'name'     => __( 'Eind', 'siw' ),
				'type'     => 'datetime',
				'required' => true,
				'timestamp' => true,
				'readonly' => true,
				'js_options' => [
					'dateFormat' => 'd MM yy',
					'stepMinute'      => 15,
					'controlType'     => 'select',
				],
				'admin_columns' => [
					'position' => 'after siw_agenda_start',
					'sort'     => true,
				],
			],
			[
				'name'     => __( 'Locatie', 'siw' ),
				'type'     => 'heading',
			],
			[
				'id'       => $prefix . 'locatie',
				'name'     => __( 'Naam', 'siw' ),
				'type'     => 'text',
				'required' => true,
			],
			[
				'id'       => $prefix . 'adres',
				'name'     => __( 'Adres', 'siw' ),
				'type'     => 'text',
				'required' => true,
			],
			[
				'id'       => $prefix . 'postcode',
				'name'     => __( 'Postcode', 'siw' ),
				'type'     => 'text',
				'required' => true,
			],
			[
				'id'       => $prefix . 'plaats',
				'name'     => __( 'Plaats', 'siw' ),
				'type'     => 'text',
				'required' => true,
			],
			[
				'name'     => __( 'Aanmelden', 'siw' ),
				'type'     => 'heading',
			],
			[
				'id'       => $prefix . 'aanmelden',
				'name'     => __( 'Aanmelden via:', 'siw' ),
				'type'     => 'button_group',
				'options'  => [
					'formulier'    => __( 'Aanmeldformulier Infodag', 'siw' ),
					'aangepast'    => __( 'Aangepaste tekst en link', 'siw' ),
				],
				'inline'   => true,
			],
			[
				'id'       => $prefix . 'aanmelden_toelichting',
				'name'     => __( 'Toelichting', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
				'raw'      => true,
				'options'  => [
					'teeny'         => true,
					'media_buttons' => false,
					'teeny'         => true,
					'textarea_rows' => 5,
				],
				'visible' => [ $prefix . 'aanmelden', '=', 'aangepast' ],
			],
			[
				'id'       => $prefix . 'aanmelden_link_url',
				'name'     => __( 'Link om aan te melden', 'siw' ),
				'type'     => 'url',
				'size'     => 100,
				'visible'  => [ $prefix . 'aanmelden', '=', 'aangepast' ],
			],
			[
				'id'       => $prefix . 'aanmelden_link_tekst',
				'name'     => __( 'Tekst voor link', 'siw' ),
				'type'     => 'text',
				'size'     => 100,
				'visible'  => [ $prefix . 'aanmelden', '=', 'aangepast' ],
			],
			[
				'name'     => __( 'Programma', 'siw' ),
				'type'     => 'heading',
			],
			[
				'id'        => $prefix . 'programma',
				'name'       => __( 'Onderdelen', 'siw' ),
				'type'       => 'group',
				'clone'      => true,
				'sort_clone' => true,
				'collapsible' => true,
				'default_state' => 'collapsed',
				'add_button' => __( 'Onderdeel toevoegen', 'siw' ),
				'group_title' => [ 'field' => 'starttijd, omschrijving'],
				'fields' => [
					[
						'id' => 'omschrijving',
						'name' => __( 'Omschrijving', 'siw' ),
						'type' => 'textarea',
					],
					[
						'id' => 'starttijd',
						'name' => __( 'Starttijd', 'siw' ),
						'type' => 'time',
						'readonly' => true,
						'js_options' => [
							'timeFormat'  => 'H:mm',
							'stepMinute'  => 15,
							'controlType' => 'select',
						],
					],
					[
						'id' => 'eindtijd',
						'name' => __( 'Eindtijd', 'siw' ),
						'type' => 'time',
						'readonly' => true,
						'js_options' => [
							'timeFormat'  => 'H:mm',
							'stepMinute'  => 15,
							'controlType' => 'select',
						],
					],
				]
			]
		],
	];

	return $meta_boxes;
});

/* Standaard sorteren op startdatum */
add_filter( 'request', function( $vars ) {
	if ( ( isset( $vars['post_type'] ) && 'agenda' == $vars['post_type'] ) ) {
		$vars = array_merge( $vars, [
			'meta_key'	=> 'siw_agenda_start',
			'orderby'	=> 'meta_value'
		] );
	}
	return $vars;
} );
