<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Elements\Taxonomy_Filter;
use SIW\Util\CSS;

/**
 * Archiefpagina
 *
 * @copyright 2020-2022 SIW Internationale Vrijwilligersprojecten
 */
class Archive {

	/** Instantie van Taxonomy Filter */
	protected Taxonomy_Filter $taxonomy_filter;

	/** Init */
	public function __construct( protected string $post_type, protected array $taxonomies, protected array $archive_options ) {

		// Archive-opties
		add_filter( 'generate_blog_columns', [ $this, 'set_archive_columns' ] );
		add_filter( 'generate_blog_get_column_count', [ $this, 'set_archive_column_count' ] );
		add_filter( 'generate_blog_masonry', [ $this, 'set_archive_masonry' ] );
		add_filter( 'generate_sidebar_layout', [ $this, 'set_sidebar_layout' ] );

		// Header voor archiefpagina toevoegen
		add_action( 'generate_inside_site_container', [ $this, 'add_archive_intro' ], 10 );
		if ( $this->archive_options['taxonomy_filter'] ) {
			$this->taxonomy_filter = Taxonomy_Filter::create()->set_use_post_count( ! empty( $this->archive_options['meta_query'] ) );
			add_action( 'generate_inside_site_container', [ $this, 'add_taxonomy_filter' ], 20 );
		}

		// Query aanpassen: limit en volgorde
		add_action( 'pre_get_posts', [ $this, 'show_all_posts_on_archive' ] );
		add_action( 'pre_get_posts', [ $this, 'set_orderby' ] );
		add_action( 'pre_get_posts', [ $this, 'set_filter' ] );
	}

	/**
	 * Toon intro van archiefpagina
	 *
	 * @todo switch voor taxonomies
	 */
	public function add_archive_intro() {
		if ( ! $this->is_archive_query() ) {
			return;
		}

		?>
		<div class="grid-container">
			<div class="siw-intro">
				<?php
					do_action( "siw_{$this->post_type}_archive_intro", $this->get_archive_type() );
				?>
			</div>
		</div>
		<?php
	}

	/** Voegt taxonomy filter toe */
	public function add_taxonomy_filter() {
		if ( ! $this->is_archive_query() ) {
			return;
		}

		// Filter van huidige taxonomy niet tonen
		$taxonomies = array_keys( $this->taxonomies );
		$taxonomies = array_diff( $taxonomies, [ $this->get_archive_type() ] );

		if ( 0 === count( $taxonomies ) ) {
			return;
		}

		$grid_size = CSS::columns_to_grid_width( count( $taxonomies ) );

		echo '<div class="grid-container">';
		foreach ( $taxonomies as $taxonomy ) {
			printf(
				"<div class='grid-{%s}'>%s</div>",
				esc_attr( $grid_size ),
				$this->taxonomy_filter->set_taxonomy( "siw_{$this->post_type}_{$taxonomy}" )->generate() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</div>';
	}

	/** Toont alle posts op archiefpagina */
	public function show_all_posts_on_archive( \WP_Query $query ) {
		if ( ! $this->is_archive_query( $query ) ) {
			return;
		}
		$query->set( 'posts_per_page', -1 );
	}

	/** Zet sorteereigenschappen */
	public function set_orderby( \WP_Query $query ) {
		if ( ! $this->is_archive_query( $query ) ) {
			return;
		}
		$query->set( 'orderby', $this->archive_options['orderby'] );
		$query->set( 'order', $this->archive_options['order'] );
		if ( in_array( $this->archive_options['orderby'], [ 'meta_value', 'meta_value_num' ], true ) ) {
			$query->set( 'meta_key', $this->archive_options['meta_key'] );
		}
	}

	/** Zet meta query voor archive */
	public function set_filter( \WP_Query $query ) {
		if ( ! $this->is_archive_query( $query ) || empty( $this->archive_options['meta_query'] ) ) {
			return;
		}
		$meta_query = (array) $query->get( 'meta_query' );
		$meta_query[] = $this->archive_options['meta_query'];
		$query->set( 'meta_query', $meta_query );
	}

	/** Geeft aan of het een query voor een relevant archief is */
	protected function is_archive_query( \WP_Query $query = null ): bool {
		if ( null === $query ) {
			global $wp_the_query;
			$query = $wp_the_query;
		}

		if ( is_admin() || false === $query->is_main_query() ) {
			return false;
		}
		if ( $query->is_post_type_archive( "siw_{$this->post_type}" ) ) {
			return true;
		}
		foreach ( array_keys( $this->taxonomies ) as $taxonomy ) {
			if ( $query->is_tax( "siw_{$this->post_type}_{$taxonomy}" ) ) {
				return true;
			}
		}
		return false;
	}

	/** Bepaal archive type */
	protected function get_archive_type(): string|bool {
		if ( is_post_type_archive( "siw_{$this->post_type}" ) ) {
			return 'post_type';
		}
		foreach ( array_keys( $this->taxonomies ) as $taxonomy ) {
			if ( is_tax( "siw_{$this->post_type}_{$taxonomy}" ) ) {
				return $taxonomy;
			}
		}
		return false;
	}

	/** Zet columns aan als er meer dan 1 column is */
	public function set_archive_columns( bool $columns ): bool {
		if ( $this->is_archive_query() ) {
			return 100 !== $this->archive_options['column_count'];
		}

		return $columns;
	}

	/** Zet het aantal kolommen */
	public function set_archive_column_count( int $count ): int {
		if ( $this->is_archive_query() ) {
			return $this->archive_options['column_count'];
		}

		return $count;
	}

	/** Zet Masonry aan voor archief */
	public function set_archive_masonry( mixed $masonry ): mixed {
		if ( $this->is_archive_query() ) {
			return $this->archive_options['masonry'];
		}
		return $masonry;
	}

	/** Zet sitebar-layout voor archive */
	public function set_sidebar_layout( string $layout ) : string {
		if ( $this->is_archive_query() ) {
			return $this->archive_options['sidebar_layout'];
		}
		return $layout;
	}
}
