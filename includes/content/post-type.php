<?php declare(strict_types=1);

namespace SIW\Content;

/**
 * Class om een custom post type toe te voegen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Post_Type {
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

	public function __construct( string $post_type, array $args, array $labels, string $single_slug, string $archive_slug ) {
		$this->post_type = $post_type;
		$this->args = $args;
		$this->labels = $labels;
		$this->single_slug = $single_slug;
		$this->archive_slug = $archive_slug;

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
			'rewrite'             => [
				'slug'       => $this->single_slug,
				'with_front' => false,
				'pages'      => false,
				'feeds'      => false,
			],
			'capability_type'     => $this->post_type,
			'map_meta_cap'        => true,
		];
		$args = wp_parse_args( $this->args, $defaults );

		register_post_type( "siw_{$this->post_type}", $args );
	}
}
