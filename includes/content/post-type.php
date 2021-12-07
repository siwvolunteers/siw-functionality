<?php declare(strict_types=1);

namespace SIW\Content;

/**
 * Class om een custom post type toe te voegen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Post_Type {

	/** Init */
	public function __construct( protected string $post_type, protected array $args, protected array $labels, protected string $single_slug, protected string $archive_slug ) {
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
