<?php

use SIW\Formatting;
use SIW\Elements\Taxonomy_Filter;

/**
 * Class om een custom content type toe te voegen
 * 
 * - Custom post type
 * - Taxonomieën
 * - Title
 * - Intro en filter voor archiefpagina
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
abstract class SIW_Content_Type {

	/**
	 * Post type
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Slug voor post
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
	 * Taxonomieën
	 *
	 * @var array
	 */
	protected $taxonomies;

	/**
	 * Icoon voor admin-menu
	 *
	 * @var string
	 */
	protected $menu_icon;

	/**
	 * Capability type
	 *
	 * @var string
	 */
	protected $capability_type = 'post';
	
	/**
	 * Post sorteren op titel
	 *
	 * @var bool
	 */
	protected $sort_by_title = false;

	/**
	 * Geeft aan of taxonomy filter getoond moet worden
	 *
	 * @var bool
	 */
	protected $show_taxonomy_filter = false;

	/**
	 * Instantie van Taxonomy Filter
	 *
	 * @var SIW_Element_Taxonomy_Filter
	 */
	protected $taxonomy_filter;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->meta_box_fields = $this->get_meta_box_fields();
		$this->taxonomies = $this->get_taxonomies();
		
		new \SIW_Post_Type(
			$this->post_type,
			$this->get_args(),
			$this->get_labels(),
			$this->get_meta_box_fields(),
			$this->single_slug,
			$this->archive_slug
		);
		
		foreach ( $this->taxonomies as $taxonomy ) {
			new \SIW_Taxonomy( $taxonomy['taxonomy'], $this->post_type, $taxonomy['labels'], $taxonomy['args'], $taxonomy['slug'] );
		}
		
		/* Header voor archiefpagina toevoegen*/
		add_action( 'after_page_header', [ $this, 'add_archive_header' ] );

		/* Titles */
		add_filter( 'single_post_title', [ $this, 'get_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_the_archive_title', [ $this, 'get_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_generated_archive_excerpt', [ $this, 'set_seo_description' ], 10, 2 );
		add_filter( 'kadence_title', [ $this, 'get_title'] );

		/* Alle posts tonen op archiefpagina */
		add_action( 'pre_get_posts', [ $this, 'show_all_posts_on_archive'] );

		if ( $this->show_taxonomy_filter ) {
			$this->taxonomy_filter = new Taxonomy_Filter();
		}
	}

	/**
	 * Zet args voor post type
	 * 
	 * @return array
	 */
	protected function get_args() {
		$args = [
			'menu_icon'       => $this->menu_icon,
			'capability_type' => $this->capability_type,
		];
		return $args;
	}

	/**
	 * Geeft labels terug
	 * 
	 * @return array
	 */
	abstract protected function get_labels();

	/**
	 * Geeft taxonomies terug
	 * 
	 * @return array
	 */
	abstract protected function get_taxonomies();

	/**
	 * Geeft meta box fields terug
	 * 
	 * @return array
	 */
	abstract protected function get_meta_box_fields();

	/**
	 * Undocumented function
	 * 
	 * @return array
	 */
	abstract protected function get_archive_intro( $archive_type );

	/**
	 * Undocumented function
	 *
	 * @param string $title
	 * @return string
	 */
	abstract protected function get_single_seo_title( $title );

	/**
	 * Undocumented function
	 *
	 * @param string $title
	 * @param string $archive_type
	 * @param \WP_Term $term
	 * @return string
	 */
	abstract protected function get_archive_seo_title( $title, $archive_type, $term );
	
	/**
	 * Undocumented function
	 *
	 * @param string $title
	 * @return string
	 */
	abstract protected function get_single_title( $title );

	/**
	 * Undocumented function
	 *
	 * @param string $title
	 * @param string $archive_type
	 * @return string
	 */
	abstract protected function get_archive_title( $title, $archive_type );

	/**
	 * Past de SEO titel aan
	 *
	 * @param string $title
	 * @param \WP_Term $term
	 * @return string
	 */
	public function get_seo_title( $title, $term ) {

		$archive_type = $this->get_archive_type();

		if ( is_singular( "siw_{$this->post_type}" ) ) {
			return $this->get_single_seo_title( $title );
		}
		elseif ( false != $archive_type ) {
			return $this->get_archive_seo_title( $title, $archive_type, $term );
		}

		return $title;
	}

	/**
	 * Past SEO-beschrijving aan
	 *
	 * @param string $description
	 * @param \WP_Term $term
	 */
	public function set_seo_description( $description, $term ) {
		if ( ! is_a( $term, 'WP_Term') ) {
			return $description;
		}
		return $description;
	}

	/**
	 * Zet titel
	 *
	 * @param string $title
	 * @return string
	 */
	public function get_title( $title ) {

		$archive_type = $this->get_archive_type();

		if ( is_singular( "siw_{$this->post_type}" ) ) {
			return $this->get_single_title( $title );
		}
		elseif ( false != $archive_type ) {
			return $this->get_archive_title( $title, $archive_type );
		}

		return $title;
	}

	/**
	 * Voegt archive header toe
	 * 
	 * @todo escaping
	 * @todo markup voor meerdere taxonomiën
	 */
	public function add_archive_header() {
		$archive_type = $this->get_archive_type();
		if ( false == $archive_type ) {
			return;
		}

		$archive_intro = $this->get_archive_intro( $archive_type );
		?>
		<div class="container">
			<div class="row siw-archive-intro">
				<div class="col-md-12">
					<?php echo Formatting::array_to_text( $archive_intro );?>
				</div>
			</div>
		</div>
		<?php
		if ( $this->show_taxonomy_filter ) {
			foreach ( $this->taxonomies as $taxonomy ) {
				if ( $archive_type != $taxonomy['taxonomy'] ) {
					echo $this->taxonomy_filter->generate( "siw_{$this->post_type}_{$taxonomy['taxonomy']}" );
				}
			}
		}

	}

	/**
	 * Bepaal archive type 
	 * 
	 * @return string|bool
	 */
	protected function get_archive_type() {
		if ( is_post_type_archive( "siw_{$this->post_type}" ) ) {
			return 'post_type';
		}
		foreach ( $this->taxonomies as $taxonomy ) {
			if ( is_tax("siw_{$this->post_type}_{$taxonomy['taxonomy']}") ) {
				return $taxonomy['taxonomy'];
			}
		}
		return false;
	}

	/**
	 * Toont alle posts op archiefpagina
	 *
	 * @param WP_Query $query
	 * 
	 * @todo archive pagina's van taxonomies?
	 */
	public function show_all_posts_on_archive( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {
			if ( is_post_type_archive( "siw_{$this->post_type}" ) ) {
				$query->set('posts_per_page', -1 );

				if ( $this->sort_by_title ) {
					$query->set( 'order' , 'asc' );
					$query->set( 'orderby', 'title');
				}
			}
		}
	}


}
