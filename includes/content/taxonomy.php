<?php declare(strict_types=1);

namespace SIW\Content;

/**
 * Class om taxonomy toe te voegen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Taxonomy {

	/** Constructor */
	public function __construct( protected string $taxonomy, protected string $post_type, protected array $settings ) {
		add_action( 'init', [ $this, 'register'] );
		add_filter( 'taxonomy_template', [ $this, 'set_archive_template' ], 10, 3 ); 
	}

	/** Registreert taxonomie */
	public function register() {
		$rewrite = [
			'slug'         => $this->settings['slug'] ?? "siw_{$this->post_type}_{$this->taxonomy}",
			'with_front'   => false,
			'hierarchical' => false,
		];

		$default_args = [
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_admin_column'  => false,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => false,
		];

		$args = wp_parse_args( $this->settings['args'], $default_args );

		$args['rewrite'] = $rewrite;
		$args['labels'] = $this->settings['labels'];

		register_taxonomy( "siw_{$this->post_type}_{$this->taxonomy}", "siw_{$this->post_type}", $args );
	}

	/** Registreert template voor taxonomy-archiefpagina */
	public function set_archive_template( string $template, string $type, array $templates ): string {
		if ( in_array( "taxonomy-siw_{$this->post_type}_{$this->taxonomy}.php", $templates ) ) {
			$template = locate_template( "archive-siw_{$this->post_type}.php" );
		}
		return $template;
	}
}
