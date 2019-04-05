<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class om een custom post type toe te voegen
 * 
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_CPT {

	/**
	 * Post type
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Array met labels
	 *
	 * @var array
	 */
	protected $labels;

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $taxonomies = [];

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected $single_slug;

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected $archive_slug;

	public function __construct( $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Registreert taxonomy
	 *
	 * @param string $taxonomy
	 * @param array $labels
	 * @param array $args
	 * @param string $slug
	 */
	public function register_taxonomy( $taxonomy, $labels, $args, $slug ) {
		$this->taxonomies[] = [
			'taxonomy' => $taxonomy,
			'labels'   => $labels,
			'args'     => $args,
			'slug'     => $slug
		];
	}

	/**
	 * Registreert CPT
	 *
	 * @param array $args
	 * @param array $labels
	 * @param string $slug
	 */
	public function register( $args, $labels, $single_slug, $archive_slug = true ) {

		$this->single_slug = $single_slug;
		$this->archive_slug = $archive_slug;
		$this->set_labels( $labels );
		$this->set_args( $args );
		add_filter( 'single_template', [ $this, 'register_single_template'], 10, 3 );
		add_filter( 'archive_template', [ $this, 'register_archive_template'], 10, 3);
		add_filter( 'taxonomy_template', [ $this, 'register_taxonomy_templates'], 10, 3);

		add_action( 'init', [ $this, 'register_taxonomies'] );
		add_action( 'init', [ $this, 'register_post_type'] );

		add_action( 'pre_get_posts', [ $this, 'show_all_posts_on_archive'] );
	}

	/**
	 * Zet de labels van de CPT
	 *
	 * @param array $labels
	 */
	public function set_labels( $labels ) {
	
		$this->labels = $labels;
	}

	/**
	 * Zet de eigenschappen van de CPT
	 *
	 * @param array $args
	 */
	public function set_args( $args ) {
		$defaults = [
			'label'               => '',
			'description'         => '',
			'labels'              => $this->labels,
			'supports'            => ['title', 'excerpt'],
			'taxonomies'          => ['siw_tm_country_continent'], //array( 'category', 'post_tag' ), //TODO:
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			//'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => $this->archive_slug,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $this->get_rewrite(),
			//'capability_type'     => $this->post_type,
			'map_meta_cap'        => true,
		];
		$this->args = wp_parse_args( $args, $defaults );
	}

	/**
	 * Geeft rewrite rules terug
	 * 
	 * @return strings
	 */
	protected function get_rewrite() {
		$rewrite = [
			'slug'       => $this->single_slug,
			'with_front' => false,
			'pages'      => false,
			'feeds'      => false,
		];
		return $rewrite;
	}

	/**
	 * Registreert post eyp
	 */
	public function register_post_type() {
		register_post_type( "siw_{$this->post_type}", $this->args);
	}

	/**
	 * Registreert taxonomieën
	 */
	public function register_taxonomies() {
		$default_args = [
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => false,
			'meta_box_cb'        => false,
		];

		foreach ( $this->taxonomies as $taxonomy ) {
			$rewrite = [
				'slug'                       => $taxonomy['slug'],
				'with_front'                 => false,
				'hierarchical'               => false,
			];	
			$args = $taxonomy['args'];
			$args['rewrite'] = $rewrite;
			$args['labels'] = $taxonomy['labels'];

			$args = wp_parse_args($args, $default_args );
			register_taxonomy( "siw_{$this->post_type}_{$taxonomy['taxonomy']}", "siw_{$this->post_type}", $args );
		}
	}

	/**
	 * Registreert template voor losse post
	 *
	 * @param string $template
	 * @param string $type
	 * @param array $templates
	 * @return string
	 */
	public function register_single_template( $template, $type, $templates ) {
		if ( in_array( "single-siw_{$this->post_type}.php", $templates ) && SIW_Util::template_exists( "single-{$this->post_type}.php") ) {
			$template = SIW_TEMPLATES_DIR . "/single-{$this->post_type}.php";
		}
		return $template;
	}

	/**
	 * Registreert template voor archiefpagina
	 *
	 * @param string $template
	 * @param string $type
	 * @param array $templates
	 * @return string
	 */
	public function register_archive_template( $template, $type, $templates ) {
		if ( in_array( "archive-siw_{$this->post_type}.php", $templates ) && SIW_Util::template_exists( "archive-{$this->post_type}.php") ) {
			$template = SIW_TEMPLATES_DIR . "/archive-{$this->post_type}.php";
		}
		return $template;
	}

	/**
	 * Registreert template voor taxonomy-archiefpagina
	 *
	 * @param string $template
	 * @param string $type
	 * @param array $templates
	 * @return string
	 */
	public function register_taxonomy_templates( $template, $type, $templates ) {
		foreach ( $this->taxonomies as $taxonomy ) {
			$taxonomy = 'continent';
			if ( in_array( "taxonomy-siw_{$this->post_type}_{$taxonomy}.php", $templates ) && SIW_Util::template_exists( "archive-{$this->post_type}.php") ) {
				$template = SIW_TEMPLATES_DIR . "/archive-{$this->post_type}.php";
			}
			}
		return $template;
	}

	/**
	 * Toont alle posts op archiefpagina
	 *
	 * @param WP_Query $query
	 */
	public function show_all_posts_on_archive( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {
			if ( is_post_type_archive( "siw_{$this->post_type}" ) ) {
				$query->set('posts_per_page', -1 );
			}
		}
	}
}
