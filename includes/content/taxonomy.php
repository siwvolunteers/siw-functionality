<?php declare(strict_types=1);

namespace SIW\Content;

/**
 * Class om taxonomy toe te voegen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Taxonomy {

	/**
	 * Taxonomie
	 */
	protected string $taxonomy;
	
	/**
	 * Post type
	 */
	protected string $post_type;

	/**
	 * Instellingen
	 */
	protected array $settings;

	/**
	 * Constructor
	 *
	 * @param string $taxonomy
	 * @param string $post_type
	 * @param array $args
	 */
	public function __construct( string $taxonomy, string $post_type, array $settings ) {

		$this->taxonomy = $taxonomy;
		$this->post_type = $post_type;
		$this->settings = $settings;

		add_action( 'init', [ $this, 'register'] );
		add_filter( 'taxonomy_template', [ $this, 'set_archive_template' ], 10, 3 ); 
	}

	/**
	 * Registreert taxonomie
	 */
	public function register() {
		$rewrite = [
			'slug'                       => $this->settings['slug'] ?? "siw_{$this->post_type}_{$this->taxonomy}",
			'with_front'                 => false,
			'hierarchical'               => false,
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

	/**
	 * Registreert template voor taxonomy-archiefpagina
	 *
	 * @param string $template
	 * @param string $type
	 * @param array $templates
	 * 
	 * @return string
	 */
	public function set_archive_template( string $template, string $type, array $templates ) : string {
		if ( in_array( "taxonomy-siw_{$this->post_type}_{$this->taxonomy}.php", $templates ) ) {
			$template = locate_template( "archive-siw_{$this->post_type}.php" );
		}
		return $template;
	}
}
