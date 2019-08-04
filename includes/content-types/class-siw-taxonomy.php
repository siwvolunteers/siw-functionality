<?php

/**
 * Class om taxonomy toe te voegen
 * 
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_Taxonomy {

	/**
	 * Taxonomie
	 *
	 * @var string
	 */
	protected $taxonomy;
	
	/**
	 * Constructor
	 *
	 * @param string $taxonomy
	 * @param string $post_type
	 * @param array $labels
	 * @param array $args
	 * @param string $slug
	 */
	public function __construct( $taxonomy, $post_type, $labels, $args, $slug ) {

		$this->taxonomy = $taxonomy;
		$this->post_type = $post_type;
		$this->labels = $labels;
		$this->args = $args;
		$this->slug = $slug;

		add_filter( 'taxonomy_template', [ $this, 'register_template'], 10, 3 );
		add_action( 'init', [ $this, 'register'] );
	}

	/**
	 * Registreert taxonomie
	 */
	public function register() {
		$rewrite = [
			'slug'                       => $this->slug,
			'with_front'                 => false,
			'hierarchical'               => false,
		];

		$default_args = [
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_admin_column'  => false,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => false,
			'meta_box_cb'        => false,
		];

		$args = wp_parse_args( $this->args, $default_args );

		$args['rewrite'] = $rewrite;
		$args['labels'] = $this->labels;

		register_taxonomy( "siw_{$this->post_type}_{$this->taxonomy}", "siw_{$this->post_type}", $args );
	}

	/**
	 * Registreert template voor taxonomy-archiefpagina
	 *
	 * @param string $template
	 * @param string $type
	 * @param array $templates
	 * @return string
	 */
	public function register_template( $template, $type, $templates ) {
		if ( in_array( "taxonomy-siw_{$this->post_type}_{$this->taxonomy}.php", $templates ) && SIW_Util::template_exists( "archive-{$this->post_type}.php") ) {
			$template = SIW_TEMPLATES_DIR . "/archive-{$this->post_type}.php";
		}
		return $template;
	}
}
