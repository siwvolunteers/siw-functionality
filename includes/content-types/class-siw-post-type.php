<?php

/**
 * Class om een custom post type toe te voegen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Post_Type {
	/**
	 * Post type
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Labels
	 *
	 * @var array
	 */
	protected $labels;

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Slug voor losse post
	 *
	 * @var string
	 */
	protected $single_slug;

	/**
	 * Slug voor archiefpagina
	 *
	 * @var string
	 */
	protected $archive_slug;

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $meta_box_fields;

	/**
	 * Undocumented function
	 *
	 * @param string $post_type
	 * @param array $args
	 * @param array $labels
	 * @param string $single_slug
	 * @param string $archive_slug
	 */
	public function __construct( $post_type, $args, $labels, $meta_box_fields, $single_slug, $archive_slug = true ) {
		$this->post_type = $post_type;
		$this->args = $args;
		$this->labels = $labels;
		$this->meta_box_fields = $meta_box_fields;
		$this->single_slug = $single_slug;
		$this->archive_slug = $archive_slug;

		add_filter( 'single_template', [ $this, 'register_single_template'], 10, 3 );
		add_filter( 'archive_template', [ $this, 'register_archive_template'], 10, 3 );
		add_filter( 'rwmb_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'init', [ $this, 'register_post_type'] );
	}

	/**
	 * Registreert post type
	 */
	public function register_post_type() {
		$defaults = [
			'label'               => '',
			'description'         => '',
			'labels'              => $this->labels,
			'menu_icon'           => null,
			'supports'            => ['title', 'excerpt'],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => $this->archive_slug,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $this->get_rewrite(),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		];
		$args = wp_parse_args( $this->args, $defaults );

		register_post_type( "siw_{$this->post_type}", $args);
	}

	/**
	 * Geeft rewrite rules terug
	 * 
	 * @return array
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
	 * Registreert template voor losse post
	 *
	 * @param string $template
	 * @param string $type
	 * @param array $templates
	 * @return string
	 */
	public function register_single_template( $template, $type, $templates ) {
		if ( in_array( "single-siw_{$this->post_type}.php", $templates ) && \SIW\Util::template_exists( "single-{$this->post_type}.php") ) {
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
		if ( in_array( "archive-siw_{$this->post_type}.php", $templates ) && \SIW\Util::template_exists( "archive-{$this->post_type}.php") ) {
			$template = SIW_TEMPLATES_DIR . "/archive-{$this->post_type}.php";
		}
		return $template;
	}

	/**
	 * Voegt meta box toe
	 *
	 * @param array $meta_boxes
	 * @return array
	 */
	public function add_meta_box( $meta_boxes ) {
		if ( empty( $this->meta_box_fields ) ) {
			return $meta_boxes;
		}
		$meta_boxes[] = [
			'id'          => "siw_{$this->post_type}",
			'title'       => $this->labels['singular_name'],
			'post_types'  => "siw_{$this->post_type}",
			'toggle_type' => 'slide',
			'context'     => 'normal',
			'priority'    => 'high',
			'fields'      => $this->meta_box_fields,
		];
		return $meta_boxes;
	}


}