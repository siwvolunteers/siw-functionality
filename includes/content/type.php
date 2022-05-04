<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Util\CSS;

/**
 * Class om een custom content type toe te voegen
 *
 * - Custom post type
 * - Taxonomieën
 * - Archiefpagina (header, layout, filter)
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
abstract class Type {

	/** Basis voor post type */
	protected string $post_type;

	/** Dashicon voor post type */
	protected string $menu_icon;

	/** Is dit een public post type */
	protected bool $public = true;

	/** Taxonomieën */
	protected array $taxonomies;

	/** Slug */
	protected string $slug;

	/** Sidebar layout van single posts */
	protected string $single_sidebar_layout = 'no-sidebar';

	/**
	 * Breedte van single post
	 *
	 * Desktop|tablet|mobile
	 */
	protected string $single_width = 'desktop';

	/** Heeft post type filter voor taxonomies? */
	protected bool $archive_taxonomy_filter = false;

	/** Gebruikt post type masonry? */
	protected bool $archive_masonry = false;

	/** Breedte van kolom in archive */
	protected int $archive_column_width = 100;

	/** Sidebar layout van archive */
	protected string $archive_sidebar_layout = 'no-sidebar';

	/**
	 * Volgorde van posts in archive
	 *
	 * ASC|DESC
	 */
	protected string $archive_order = 'DESC';

	/**
	 * Volgorde van posts in admin
	 *
	 * ASC|DESC
	 */
	protected string $admin_order = 'DESC';

	/** Waarop moeten posts gesorteerd worden */
	protected string $orderby = 'date';

	/** Als `orderby` meta_key is, welke meta_key dan */
	protected string $orderby_meta_key = '';

	/** Meta query om te filteren op 'actieve' posts */
	protected array $active_posts_meta_query = [];

	/**
	 * Kan post type in carousel gebruikt worden
	 */
	protected bool $has_carousel_support = false;

	/** Directory voor uploads bij post type */
	protected string $upload_subdir;

	/** Init */
	public static function init() {
		$self = new static();
		$self->taxonomies = $self->get_taxonomies();

		// CPT registreren
		new Post_Type(
			$self->post_type,
			[
				'public'    => $self->public,
				'menu_icon' => $self->menu_icon,
			],
			$self->get_labels(),
			$self->slug,
			$self->slug
		);

		// Taxonomieën registreren
		foreach ( $self->taxonomies as $taxonomy => $settings ) {
			new Taxonomy( $taxonomy, $self->post_type, $settings );
		}

		// Metabox registreren
		add_filter( "siw_{$self->post_type}_meta_box_fields", [ $self, 'get_meta_box_fields' ] );
		new Meta_Box(
			$self->post_type,
			$self->get_labels()['singular_name'],
			$self->taxonomies
		);

		// Standaard volgorde in Admin scherm
		add_action( 'pre_get_posts', [ $self, 'set_default_orderby' ] );

		// Instellingen voor publieke post types
		if ( $self->public ) {
			// Single post
			add_action( "siw_{$self->post_type}_content", [ $self, 'add_single_content' ] );
			add_action( 'wp_enqueue_scripts', [ $self, 'set_single_width' ], 50 );
			add_filter( 'siw_social_share_post_types', [ $self, 'set_social_share_cta' ] );

			$self->active_posts_meta_query = $self->get_active_posts_meta_query();

			// Archive
			new Archive(
				$self->post_type,
				$self->taxonomies,
				[
					'taxonomy_filter' => $self->archive_taxonomy_filter,
					'masonry'         => $self->archive_masonry,
					'column_count'    => $self->archive_column_width,
					'order'           => $self->archive_order,
					'orderby'         => $self->orderby,
					'meta_key'        => $self->orderby_meta_key,
					'meta_query'      => $self->active_posts_meta_query,
					'sidebar_layout'  => $self->archive_sidebar_layout,
				]
			);
			add_filter( 'post_type_archive_title', [ $self, 'set_archive_title' ] );

			add_action( "siw_{$self->post_type}_archive_intro", [ $self, 'set_archive_intro' ] );
			add_action( "siw_{$self->post_type}_archive_content", [ $self, 'add_archive_content' ] );

			if ( ! empty( $self->active_posts_meta_query ) && ! empty( $self->taxonomies ) ) {
				add_filter( 'siw_update_terms_taxonomies', [ $self, 'set_update_terms_taxonomies' ] );
				add_filter( 'siw_update_terms_meta_query', [ $self, 'set_update_terms_meta_query' ], 10, 2 );
			}

			if ( ! empty( $self->active_posts_meta_query ) ) {
				add_action( 'admin_menu', [ $self, 'add_admin_active_post_count' ], PHP_INT_MAX );
			}

			// Carousel
			if ( $self->has_carousel_support ) {
				add_filter( 'siw_carousel_post_types', [ $self, 'add_carousel_post_type' ] );
				add_filter( 'siw_carousel_post_type_templates', [ $self, 'add_carousel_post_type_template' ] );
				add_filter( 'siw_carousel_post_type_taxonomies', [ $self, 'add_carousel_post_type_taxonomies' ] );
			}

			// SEO TODO: titles enzo
			add_filter( 'the_seo_framework_post_meta', [ $self, 'set_seo_noindex' ], 10, 2 );

			// TODO:Help tabs

			// genereren slug en titel
			add_filter( 'wp_insert_post_data', [ $self, 'set_post_data' ], 10, 2 );

			add_filter( 'siw_cpt_upload_subdirs', [ $self, 'set_upload_subir' ] );
		}
	}

	/** Undocumented function */
	protected function initialize() {}

	/** Haalt metabox velden op */
	abstract public function get_meta_box_fields() : array;

	/** Haal taxonomieën op */
	abstract protected function get_taxonomies() : array;

	/** Haalt labels op */
	abstract protected function get_labels() : array;

	/** Undocumented function */
	public function add_single_content() {}

	/** Undocumented function */
	public function add_archive_content() {}

	/** Undocumented function */
	public function set_archive_intro( string $archive_type ) {
		echo wp_kses_post( implode( SPACE, $this->get_archive_intro( $archive_type ) ) );
	}

	/** Undocumented function */
	protected function get_archive_intro() : array {
		return [];
	}

	/** Zet titel */
	public function set_archive_title( string $archive_title ) : string {
		if (
			is_post_type_archive( "siw_{$this->post_type}" ) ||
			is_singular( "siw_{$this->post_type}" )
		) {
			$archive_title = $this->get_archive_title( $archive_title );
		}
		return $archive_title;
	}

	/** Undocumented function */
	protected function get_archive_title( string $archive_title ) : string {
		return $archive_title;
	}

	/** Undocumented function */
	protected function get_active_posts_meta_query() : array {
		return [];
	}

	/** Zet breedt van single post */
	public function set_single_width() {

		if ( ! is_singular( "siw_{$this->post_type}" ) ) {
			return;
		}
		switch ( $this->single_width ) {
			case 'tablet':
				$width = CSS::TABLET_BREAKPOINT;
				break;
			case 'mobile':
				$width = CSS::MOBILE_BREAKPOINT;
				break;
			default:
				$width = null;
		}

		if ( ! is_int( $width ) ) {
			return;
		}
		$inline_css = CSS::generate_inline_css(
			[
				'#content, .inside-page-hero.grid-container' => [
					'max-width'    => "{$width}px",
					'margin-left'  => 'auto',
					'margin-right' => 'auto',
				],
			]
		);
		wp_add_inline_style(
			'generate-style',
			$inline_css
		);
	}

	/** Zet call to action voor social share links */
	public function set_social_share_cta( array $post_types ) : array {
		$post_types[ "siw_{$this->post_type}" ] = $this->get_social_share_cta();
		return $post_types;
	}

	/** Zet social share CTA */
	protected function get_social_share_cta() : string {
		return __( 'Deel deze pagina', 'siw' );
	}

	/** Zet SEO-noindex */
	public function set_seo_noindex( array $meta, int $post_id ) : array {
		if ( "siw_{$this->post_type}" === get_post_type( $post_id ) ) {
			$meta['_genesis_noindex'] = intval( $this->get_seo_noindex( $post_id ) );
		}
		return $meta;
	}

	/** Bepaal SEO-noindex */
	protected function get_seo_noindex( int $post_id ) : bool {
		return false;
	}

	/** Genereert titel slug op basis van eigenschappen */
	public function set_post_data( array $data, array $postarr ) : array {

		if ( in_array( $data['post_status'], [ 'draft', 'pending', 'auto-draft' ], true ) ) {
			return $data;
		}

		if ( "siw_{$this->post_type}" !== $data['post_type'] ) {
			return $data;
		}

		$data['post_title'] = $this->generate_title( $data, $postarr );
		$slug = sanitize_title( $this->generate_slug( $data, $postarr ) );
		$data['post_name'] = wp_unique_post_slug( $slug, $postarr['ID'], $data['post_status'], $data['post_type'], $data['post_parent'] );
		return $data;
	}

	/** Genereert titel */
	protected function generate_title( array $data, array $postarr ) : string {
		return $data['post_title'];
	}

	/** Genereert slug */
	protected function generate_slug( array $data, array $postarr ) : string {
		return $data['post_name'];
	}

	/**
	 * Zet standaard volgorde voor admin scherm
	 *
	 * @todo netjes maken? + inverse van archive
	 */
	public function set_default_orderby( \WP_Query $query ) {

		// Afbreken
		if ( ! $query->is_admin || "siw_{$this->post_type}" !== $query->get( 'post_type' ) ) {
			return;
		}

		if ( empty( $query->get( 'orderby' ) ) ) {
			$query->set( 'orderby', $this->orderby );
		}

		if ( empty( $query->get( 'meta_key' ) ) && 'meta_value' === $this->orderby ) {
			$query->set( 'meta_key', $this->orderby_meta_key );
		}

		if ( empty( $query->get( 'order' ) ) ) {
			$query->set( 'order', $this->admin_order );
		}
	}

	/** Toon teller met aantal actieve posts */
	public function add_admin_active_post_count() {
		global $submenu;

		$submenu_index = "edit.php?post_type=siw_{$this->post_type}";

		if ( ! isset( $submenu[ $submenu_index ] ) ) {
			return;
		}

		$cpt_menu = $submenu[ $submenu_index ];
		$menu_item = wp_list_filter(
			$cpt_menu,
			[ 2 => $submenu_index ]
		);
		$menu_item_index = ! empty( $menu_item ) ? key( $menu_item ) : null;

		$posts = get_posts(
			[
				'post_type'  => "siw_{$this->post_type}",
				'meta_query' => [ $this->active_posts_meta_query ], // phpcs:ignore
				'limit'      => -1,
				'return'     => 'ids',
			]
		);

		$count = count( $posts );
		if ( $count > 0 && $menu_item_index ) {
			$submenu[ $submenu_index ][ $menu_item_index ][0] .= ' <span class="awaiting-mod">' . number_format_i18n( $count ) . '</span>'; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}

	/** Voegt post type toe aan carousel widget */
	public function add_carousel_post_type( array $post_types ) : array {
		$post_types[ "siw_{$this->post_type}" ] = $this->post_type; // TODO: juiste label gebruiken
		return $post_types;
	}

	/** Voegt taxonomieën toe aan carousel widget */
	public function add_carousel_post_type_taxonomies( array $post_type_taxonomies ) : array {
		foreach ( $this->taxonomies as $taxonomy => $settings ) {
			$post_type_taxonomies[ "siw_{$this->post_type}" ][ "siw_{$this->post_type}_{$taxonomy}" ] = $settings['labels']['name'];
		}
		return $post_type_taxonomies;
	}

	/** Zet template voor carousel */
	public function add_carousel_post_type_template( array $post_type_templates ) : array {
		$post_type_templates[ "siw_{$this->post_type}" ] = locate_template( "content-siw_{$this->post_type}.php" );
		return $post_type_templates;
	}

	/** Zet subdirectory voor uploads */
	public function set_upload_subir( array $subdirs ) : array {
		if ( isset( $this->upload_subdir ) ) {
			$subdirs[ "siw_{$this->post_type}" ] = $this->upload_subdir;
		}
		return $subdirs;
	}

	/** Zet taxonomiën om bij te werken via batch */
	public function set_update_terms_taxonomies( array $taxonomies ) : array {
		foreach ( array_keys( $this->taxonomies ) as $taxonomy ) {
			$taxonomies[] = "siw_{$this->post_type}_{$taxonomy}";
		}
		return $taxonomies;
	}

	/** Zet meta query voor update van terms*/
	public function set_update_terms_meta_query( array $meta_query, string $term_taxonomy ) : array {

		foreach ( array_keys( $this->taxonomies ) as $taxonomy ) {
			if ( "siw_{$this->post_type}_{$taxonomy}" === $term_taxonomy ) {
				$meta_query = [ $this->active_posts_meta_query ];
				break;
			}
		}

		return $meta_query;
	}
}
