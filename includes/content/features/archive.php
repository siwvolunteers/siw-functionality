<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Active_Posts as I_Active_Posts;
use SIW\Interfaces\Content\Archive_Columns as I_Archive_Columns;
use SIW\Interfaces\Content\Archive_Order as I_Archive_Order;
use SIW\Interfaces\Content\Type as I_Type;
use SIW\Interfaces\Content\Taxonomies as I_Taxonomies;
use SIW\Util\CSS;

/**
 * Archief
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Archive extends Base {

	/** Archief kolommen */
	protected I_Archive_Columns $archive_columns;

	/** Archief volgorde */
	protected I_Archive_Order $archive_order;

	/** Actieve posts */
	protected I_Active_Posts $active_posts;

	/** Taxonomieëm */
	protected I_Taxonomies $taxonomies;

	/** Init */
	protected function __construct( protected I_Type $type ) {}

	/** Zet taxonomieën */
	public function set_taxonomies( I_Taxonomies $taxonomies ): static {
		$this->taxonomies = $taxonomies;
		return $this;
	}

	/** Zet active posts */
	public function set_active_posts( I_Active_Posts $active_posts ): static {
		$this->active_posts = $active_posts;
		return $this;
	}

	/** Zet archief-kolommen */
	public function set_archive_columns( I_Archive_Columns $archive_columns ): static {
		$this->archive_columns = $archive_columns;
		return $this;
	}

	/** Zet archief-volgorde */
	public function set_archive_order( I_Archive_Order $archive_order ): static {
		$this->archive_order = $archive_order;
		return $this;
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
		if ( $query->is_post_type_archive( $this->type->get_post_type() ) ) {
			return true;
		}

		if ( isset( $this->taxonomies ) ) {
			foreach ( array_keys( $this->taxonomies->get_taxonomies() ) as $taxonomy ) {
				if ( $query->is_tax( "{$this->type->get_post_type()}_{$taxonomy}" ) ) {
					return true;
				}
			}
		}
		return false;
	}

	/** Bepaal archive type */
	protected function get_archive_type(): ?string {
		if ( is_post_type_archive( $this->post_type ) ) {
			return 'post_type';
		}
		foreach ( array_keys( $this->taxonomies?->get_taxonomies() ) as $taxonomy ) {
			if ( is_tax( "{$this->post_type}_{$taxonomy}" ) ) {
				return $taxonomy;
			}
		}
		return null;
	}

	#[Filter( 'generate_blog_columns' )]
	/** Zet columns aan als er meer dan 1 column is */
	public function set_use_columns( bool $use_columns ): bool {
		if ( ! isset( $this->archive_columns ) || ! $this->is_archive_query() ) {
			return $use_columns;
		}
		return 1 !== $this->archive_columns->get_archive_column_count();
	}

	#[Filter( 'generate_blog_get_column_count' )]
	/** Zet het aantal kolommen */
	public function set_column_count( int $count ): int {
		if ( ! isset( $this->archive_columns ) || ! $this->is_archive_query() ) {
			return $count;
		}
		return CSS::columns_to_grid_width( $this->archive_columns->get_archive_column_count() );
	}

	#[Filter( 'generate_blog_masonry' )]
	/** Zet Masonry aan voor archief */
	public function set_use_masonry( mixed $use_masonry ): mixed {
		if ( ! isset( $this->archive_columns ) || ! $this->is_archive_query() ) {
			return $use_masonry;
		}
		return $this->archive_columns->get_use_masonry();
	}

	#[Action( 'pre_get_posts' )]
	/** Toont alle posts op archiefpagina */
	public function show_all_posts_on_archive( \WP_Query $query ) {
		if ( ! $this->is_archive_query( $query ) ) {
			return;
		}
		$query->set( 'posts_per_page', -1 );
	}

	#[Action( 'pre_get_posts' )]
	/** Zet sorteereigenschappen */
	public function set_orderby( \WP_Query $query ) {

		if ( ! isset( $this->archive_order ) ) {
			return;
		}

		if ( ! $this->is_archive_query( $query ) ) {
			return;
		}

		$query->set( 'orderby', $this->archive_order->get_archive_orderby() );
		$query->set( 'order', $this->archive_order->get_archive_order() );
		if ( in_array( $this->archive_order->get_archive_orderby(), [ 'meta_value', 'meta_value_num' ], true ) ) {
			$query->set( 'meta_key', $this->archive_order->get_archive_orderby_meta_key() );
		}
	}

	#[Action( 'pre_get_posts' )]
	/** Zet meta query voor archive */
	public function set_filter( \WP_Query $query ) {
		if ( ! $this->is_archive_query( $query ) || ! isset( $this->active_posts ) ) {
			return;
		}
		$meta_query = (array) $query->get( 'meta_query' );
		$meta_query[] = $this->active_posts->get_active_posts_meta_query();
		$query->set( 'meta_query', $meta_query );
	}

	#[Action( 'generate_inside_site_container' )]
	/** Toon intro van archiefpagina */
	public function add_archive_intro() {
		if ( ! $this->is_archive_query() ) {
			return;
		}
		?>
		<div class="grid-container">
			<div class="siw-intro">
				<?php
					echo wp_kses_post( siw_get_option( "{$this->type->get_post_type()}.archive_intro" ) );
				?>
			</div>
		</div>
		<?php
	}

}
