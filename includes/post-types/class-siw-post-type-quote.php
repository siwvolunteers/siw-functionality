<?php
/*
 * (c)2020 SIW Internationale Vrijwilligersprojecten
 */

class SIW_Post_Type_Quote {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'init', [ $self, 'register_post_type'], 0 );
		add_action( 'init', [ $self, 'register_taxonomy'], 0 );
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_meta_boxes' ] );
		add_filter( 'wp_insert_post_data', [ $self, 'generate_slug' ], 10, 2 );
	}

	/**
	 * Registreert post type
	 */
	public function register_post_type() {
		$labels = [
			'name'               => __( 'Quotes', 'siw' ),
			'singular_name'      => __( 'Quote', 'siw' ),
			'add_new'            => __( 'Nieuwe quote', 'siw' ),
			'add_new_item'       => __( 'Voeg quote toe', 'siw' ),
			'edit_item'          => __( 'Bewerk quote', 'siw' ),
			'new_item'           => __( 'Nieuwe quote', 'siw' ),
			'all_items'          => __( 'Alle quotes', 'siw' ),
			'view_item'          => __( 'Bekijk quote', 'siw' ),
			'search_items'       => __( 'Zoek quote', 'siw' ),
			'not_found'          => __( 'Geen quotes gevonden', 'siw' ),
			'not_found_in_trash' => __( 'Geen quotes gevonden in de prullenbak', 'siw' ),
			'archives'           => __( 'Quotes', 'siw' ),
		];
		$args = [
			'label'               => __( 'Quote', 'siw' ),
			'description'         => __( 'Quote', 'siw' ),
			'labels'              => $labels,
			'supports'            => ['title'],
			'taxonomies'          => [ 'agenda_type' ],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-quote',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'event',
			'map_meta_cap'        => true,
		];
		register_post_type( 'siw_quote', $args );
	}

	/**
	 * Registreert taxonomy (soort evenement)
	 */
	public function register_taxonomy() {
		$labels = [
			'name'                       => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
			'singular_name'              => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
			'menu_name'                  => __( 'Continenten', 'siw' ),
			'all_items'                  => __( 'Alle continenten', 'siw' ),
			'add_new_item'               => __( 'Continent toevoegen', 'siw' ),
			'update_item'                => __( 'Continent bijwerken', 'siw' ),
			'view_item'                  => __( 'Bekijk continent', 'siw' ),
			'search_items'               => __( 'Zoek continenten', 'siw' ),
			'not_found'                  => __( 'Geen continenten gevonden', 'siw' ),
		];
		$args = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => false,
			'show_in_nav_menus' => false,
			'query_var'         => false,
			// 'capabilities' => [
			// 	'assign_terms' => 'edit_posts'
			// ],
		];
		register_taxonomy( 'siw_quote_continent', ['siw_quote'], $args );

		$labels = [
			'name'                       => _x( 'Projectsoort', 'Taxonomy General Name', 'siw' ),
			'singular_name'              => _x( 'Projectsoort', 'Taxonomy Singular Name', 'siw' ),
			'menu_name'                  => __( 'Projectsoort', 'siw' ),
			'all_items'                  => __( 'Alle projectsoorten', 'siw' ),
			'add_new_item'               => __( 'Projectsoort toevoegen', 'siw' ),
			'update_item'                => __( 'Projectsoort bijwerken', 'siw' ),
			'view_item'                  => __( 'Bekijk proejctsoort', 'siw' ),
			'search_items'               => __( 'Zoek projectsoorten', 'siw' ),
			'not_found'                  => __( 'Geen projectsoorten gevonden', 'siw' ),
		];
		$args = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => false,
			'show_in_nav_menus' => false,
			'query_var'         => false,
			// 'capabilities' => [
			// 	'assign_terms' => 'edit_events'
			// ],
		];
		register_taxonomy( 'siw_quote_project_type', ['siw_quote'], $args );
	}
	/**
	 * Voegt metaboxes toe
	 *
	 * @param array $meta_boxes
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes[] = [
			'id'          => 'siw_quote',
			'title'       => __( 'Quote', 'siw' ),
			'post_types'  => 'siw_quote',
			'toggle_type' => 'slide',
			'context'     => 'normal',
			'priority'    => 'high',
			'fields' => [
				[
					'id'       => 'quote',
					'name'     => __( 'Quote', 'siw' ),
					'type'     => 'textarea',
					'required' => true,
					'limit'    => 200,
				],
				[
					'id'       => 'name',
					'name'     => __( 'Naam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'          => 'country',
					'name'        => __( 'Land', 'siw' ),
					'type'        => 'select_advanced',
					'options'     => siw_get_countries( 'all', 'slug', 'array' ),
					'required'    => true,
					'placeholder' => __( 'Selecteer een land', 'siw' ),
				],
				[
					'id'             => 'continent',
					'name'           => __( 'Continent', 'siw' ),
					'type'           => 'taxonomy',
					'taxonomy'       => 'siw_quote_continent',
					'field_type'     => 'radio',
					'required'       => true,
					'remove_default' => true,
					'admin_columns'  => [
						'position'     => 'after title',
						'sort'         => true,
						'filterable'   => true,
					],
				],
				[
					'id'             => 'project_type',
					'name'           => __( 'Projectsoort', 'siw' ),
					'type'           => 'taxonomy',
					'taxonomy'       => 'siw_quote_project_type',
					'field_type'     => 'radio',
					'required'       => true,
					'remove_default' => true,
					'admin_columns'  => [
						'position'     => 'after title',
						'sort'         => true,
						'filterable'   => true,
					],
				],
			]
		];
		return $meta_boxes;
	}

	/**
	 * Genereert slug op basis van naam, soort project en land
	 *
	 * @param array $data
	 * @param array $postarr
	 * 
	 * @return array
	 */
	public function generate_slug( array $data, array $postarr ) {

		//Afbreken als het een import is
		if ( isset( $postarr['import_id'] ) ) {
			return $data;
		}

		if ( in_array( $data['post_status'], [ 'draft', 'pending', 'auto-draft' ] ) ) {
			return $data;
		}

		if ( 'siw_quote' != $data['post_type'] ) {
			return $data;
		}
		
		$data['post_title'] = sprintf(
			'%s | %s %s',
			$postarr['name'],
			get_term( $postarr['project_type'], 'siw_quote_project_type' )->name,
			siw_get_country( $postarr['country'] )->get_name()
		);
		$data['post_name'] = wp_unique_post_slug(
			sanitize_title( $data['post_title'] ),
			$postarr['ID'],
			$data['post_status'],
			$data['post_type'],
			$data['post_parent']
		);
		return $data;
	}
}
