<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */

class SIW_Post_Type_Vacatures {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_meta_boxes' ] );
		add_filter( 'request', [ $self, 'sort_by_deadline' ] );
		add_action( 'init', [ $self, 'register_post_type'], 0 );
		add_action( 'init', [ $self, 'register_taxonomy'], 0 );
		add_filter( 'the_seo_framework_post_meta', [ $self, 'set_seo_noindex' ], 10, 2 );
	}

	/**
	 * Standaard sorteren op deadline
	 *
	 * @param array $vars
	 * @return array
	 */
	public function sort_by_deadline( array $vars ) {
		if ( ( isset( $vars['post_type'] ) && 'vacatures' == $vars['post_type'] ) ) {
			$vars = array_merge( $vars, [
				'meta_key' => 'siw_vacature_deadline',
				'orderby'  => 'meta_value'
			] );
		}
		return $vars;
	}

	/**
	 * Registreert post type
	 */
	public function register_post_type() {
		$labels = [
			'name'               => _x( 'Vacatures', 'Post Type General Name', 'siw' ),
			'singular_name'      => _x( 'Vacature', 'Post Type Singular Name', 'siw' ),
			'menu_name'          => __( 'Vacatures (oud)', 'siw' ),
			'name_admin_bar'     => __( 'Vacature', 'siw' ),
			'parent_item_colon'  => __( 'Parent Item:', 'siw' ),
			'all_items'          => __( 'Alle vacatures', 'siw' ),
			'add_new_item'       => __( 'Vacature toevoegen', 'siw' ),
			'add_new'            => __( 'Toevoegen', 'siw' ),
			'new_item'           => __( 'Nieuwe vacature', 'siw' ),
			'edit_item'          => __( 'Bewerk vacature', 'siw' ),
			'update_item'        => __( 'Update vacature', 'siw' ),
			'view_item'          => __( 'Bekijk vacature', 'siw' ),
			'search_items'       => __( 'Zoek vacature', 'siw' ),
			'not_found'          => __( 'Niet gevonden', 'siw' ),
			'not_found_in_trash' => __( 'Niet gevonden in de prullenbak', 'siw' ),
		];
		$args = [
			'label'               => __( 'Vacature', 'siw' ),
			'description'         => __( 'Vacatures', 'siw' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'excerpt' ],
			'taxonomies'          => [ 'job_type' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-nametag',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'job',
			'map_meta_cap'        => true,
			'rewrite'             => [
				'slug'       => 'vacatures-oud',
				'with_front' => false,
				'pages'      => false,
				'feeds'      => false,
			],
		];
		register_post_type( 'vacatures', $args );
	}

	/**
	 * Registreert taxonomy (soort vacature)
	 */
	public function register_taxonomy() {
		$labels = [
			'name'          => _x( 'Soort vacature', 'Taxonomy General Name', 'siw' ),
			'singular_name' => _x( 'Taxonomy', 'Taxonomy Singular Name', 'siw' ),
			'menu_name'     => __( 'Soort vacature', 'siw' ),
		];
		$args = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'query_var'         => true,
			'capabilities'      => [
				'assign_terms' => 'edit_jobs'
			],
		];
		register_taxonomy( 'soort_vacature', ['vacatures'], $args );
	}

	/**
	 * Voegt metaboxes toe
	 *
	 * @param array $meta_boxes
	 * @return array
	 */
	public function add_meta_boxes( array $meta_boxes ) {
		$prefix = 'siw_vacature_';

		$meta_boxes[] = [
			'id'         => 'siw_vacature_meta',
			'title'      => __( 'Vacature', 'siw' ),
			'post_types' => 'vacatures',
			'context'    => 'normal',
			'priority'   => 'high',
			'fields' => [
				[
					'id'        => $prefix . 'betaald',
					'name'      => __( 'Betaalde vacature', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'id'       => $prefix . 'uur_per_week',
					'name'     => __( 'Aantal uur per week', 'siw' ),
					'type'     => 'text',
					'size'     => 10,
					'append'   => __( 'uur/week', 'siw' ),
				],
				[
					'id'            => $prefix . 'uitgelicht',
					'name'          => __( 'Vacature uitlichten', 'siw' ),
					'type'          => 'switch',
					'on_label'      => __( 'Ja', 'siw' ),
					'off_label'     => __( 'Nee', 'siw'),
					'desc'          => __( 'Wordt getoond in topbar', 'siw' ),
					'admin_columns' => [
						'position'    => 'after title',
						'sort'        => true,
					],
				],
				[
					'name'     => __( 'Solliciteren', 'siw' ),
					'type'     => 'heading',
				],
				[
					'id'       => $prefix . 'solliciteren_naam',
					'name'     => __( 'Naam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => $prefix . 'solliciteren_functie',
					'name'     => __( 'Functie', 'siw' ),
					'type'     => 'text',
				],
				[
					'id'       => $prefix . 'solliciteren_email',
					'name'     => __( 'E-mail', 'siw' ),
					'type'     => 'email',
					'required' => true,
				],
				[
					'id'            => $prefix . 'deadline',
					'name'          => __( 'Deadline', 'siw' ),
					'type'          => 'date',
					'timestamp'     => true,
					'inline'        => true,
					'readonly'      => true,
					'admin_columns' => [
						'position' => 'after title',
						'sort'     => true,
					],
				],
				[
					'name'     => __( 'Contactpersoon', 'siw' ),
					'type'     => 'heading',
				],
				[
					'id'       => $prefix . 'contactpersoon_naam',
					'name'     => __( 'Naam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => $prefix . 'contactpersoon_functie',
					'name'     => __( 'Functie', 'siw' ),
					'type'     => 'text',
				],
				[
					'id'       => $prefix . 'contactpersoon_email',
					'name'     => __( 'E-mail', 'siw' ),
					'type'     => 'email',
					'required' => true,
				],
				[
					'name'     => __( 'Beschrijving', 'siw' ),
					'type'     => 'heading',
				],
				[
					'id'       => $prefix . 'inleiding',
					'name'     => __( 'Inleiding', 'siw' ),
					'type'     => 'wysiwyg',
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
					'name'     => __( 'Wat ga je doen?', 'siw' ),
					'type'     => 'heading',
				],
				[
					'id'       => $prefix . 'wat_ga_je_doen',
					'name'     => __( 'Introductie', 'siw' ),
					'type'     => 'wysiwyg',
					'raw'      => true,
					'options'  => [
						'teeny'         => true,
						'media_buttons' => false,
						'teeny'         => true,
						'textarea_rows' => 5,
					],
				],
				[
					'id'         => $prefix . 'wat_ga_je_lijst',
					'name'       => __( 'Lijst', 'siw' ),
					'type'       => 'text',
					'size'       => 100,
					'clone'      => true,
					'sort_clone' => true,
					'add_button' => __( 'Toevoegen', 'siw' ),
				],
				[
					'name'     => __( 'Wie ben je?', 'siw' ),
					'type'     => 'heading',
				],
				[
					'id'       => $prefix . 'wie_ben_jij',
					'name'     => __( 'Introductie', 'siw' ),
					'type'     => 'wysiwyg',
					'raw'      => true,
					'options'  => [
						'teeny'         => true,
						'media_buttons' => false,
						'teeny'         => true,
						'textarea_rows' => 5,
					],
				],
				[
					'id'         => $prefix . 'wie_ben_jij_lijst',
					'name'       => __( 'Lijst', 'siw' ),
					'type'       => 'text',
					'size'       => 100,
					'clone'      => true,
					'sort_clone' => true,
					'add_button' => __( 'Toevoegen', 'siw' ),
				],
				[
					'name'     => __( 'Wat bieden wij?', 'siw' ),
					'type'     => 'heading',
				],
				[
					'id'       => $prefix . 'wat_bieden_wij_jou',
					'name'     => __( 'Introductie', 'siw' ),
					'type'     => 'wysiwyg',
					'raw'      => true,
					'options'  => [
						'teeny'         => true,
						'media_buttons' => false,
						'teeny'         => true,
						'textarea_rows' => 5,
					],
				],
				[
					'id'         => $prefix . 'wat_bieden_wij_jou_lijst',
					'name'       => __( 'Lijst', 'siw' ),
					'type'       => 'text',
					'size'       => 100,
					'clone'      => true,
					'sort_clone' => true,
					'add_button' => __( 'Toevoegen', 'siw' ),
				],
			],
		];
		return $meta_boxes;
	}

	/**
	 * Undocumented function
	 *
	 * @param array $meta
	 * @param int $post_id
	 *
	 * @return array
	 */
	function set_seo_noindex( array $meta, int $post_id ) : array {
		if ( 'vacatures' == get_post_type( $post_id ) ) {
			$meta['_genesis_noindex'] = 1;
		}
		return $meta;
	}
}
