<?php declare(strict_types=1);

namespace SIW\Content\Post_Types;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Config;
use SIW\Content\Post\Post;
use SIW\Data\Post_Type_Support;
use SIW\Elements\Taxonomy_Filter;
use SIW\Facades\Meta_Box;
use SIW\Helpers\Template;
use SIW\Structured_Data\Thing;

abstract class Post_Type extends Base {

	final protected static function get_post_type_base(): string {
		$class_name_components = explode( '\\', static::class );
		return strtolower( end( $class_name_components ) );
	}

	final public static function get_post_type(): string {
		return 'siw_' . static::get_post_type_base();
	}

	abstract protected static function get_dashicon(): string;

	abstract protected static function get_slug(): string;

	abstract protected static function get_singular_label(): string;

	abstract protected static function get_plural_label(): string;

	abstract protected static function get_admin_columns(): array;

	abstract protected static function get_site_sortables(): array;

	/**
	 * @return Post_Type_Support[]
	 */
	abstract protected static function get_post_type_supports(): array;

	abstract protected function get_template_variables( string $type, int $post_id ): array;

	abstract public static function get_meta_box_fields(): array;

	abstract protected function get_taxonomies(): array;

	abstract protected function get_custom_post( \WP_Post|int $post ): Post;

	protected static function get_active_posts_meta_query(): array {
		return [];
	}

	protected static function get_settings_fields(): array {
		return [];
	}

	final protected static function has_active_posts_meta_query(): bool {
		return ! empty( static::get_active_posts_meta_query() );
	}

	#[Add_Filter( 'siw/update_custom_posts/post_types' )]
	final public function register_post_type_for_delete_old_post( array $post_types ) {
		if ( static::has_active_posts_meta_query() ) {
			$post_types[] = static::get_post_type();
		}

		return $post_types;
	}

	#[Add_Filter( 'siw/update_custom_posts/should_delete' )]
	final public function should_delete_post( bool $should_delete, int $post_id ): bool {
		if ( static::get_post_type() !== get_post_type( $post_id ) ) {
			return $should_delete;
		}
		return $this->get_custom_post( $post_id )->should_delete();
	}

	#[Add_Filter( 'siw/update_custom_posts/should_index' )]
	final public function should_index( bool $should_index, int $post_id ): bool {
		if ( static::get_post_type() !== get_post_type( $post_id ) ) {
			return $should_index;
		}
		return $this->get_custom_post( $post_id )->is_active();
	}

	#[Add_Action( 'pre_get_posts' )]
	final public function set_filter( \WP_Query $query ) {
		if ( ! $this->is_archive_query( $query ) || empty( $this->get_active_posts_meta_query() ) ) {
			return;
		}
		$meta_query = (array) $query->get( 'meta_query' );
		$meta_query[] = $this->get_active_posts_meta_query();
		$query->set( 'meta_query', $meta_query );
	}

	//#[Add_Filter( 'slim_seo_robots_index' )]
	final public function set_seo_robots_index( bool $index, int $post_id ): bool {
		if ( static::get_post_type() !== get_post_type( $post_id ) ) {
			return $index;
		}
		return $this->get_custom_post( $post_id )->is_active();
	}

	#[Add_Filter( 'slim_seo_breadcrumbs_args', 20 )]
	final public function set_seo_breadcrumb_args( array $args ): array {
		if ( static::get_post_type() !== get_post_type() || 1 !== count( $this->get_taxonomies() ) ) {
			return $args;
		}

		$args['taxonomy'] = static::get_post_type() . '_' . array_key_first( $this->get_taxonomies() );
		return $args;
	}

	#[Add_Filter( 'wp_insert_post_data' )]
	final public function set_post_data( array $data, array $postarr ): array {

		if ( in_array( $data['post_status'], [ 'draft', 'pending', 'auto-draft' ], true ) ) {
			return $data;
		}

		if ( static::get_post_type() !== $data['post_type'] ) {
			return $data;
		}

		$data['post_title'] = $this->generate_title( $data, $postarr );
		$slug = sanitize_title( $this->generate_slug( $data, $postarr ) );
		$data['post_name'] = wp_unique_post_slug( $slug, $postarr['ID'], $data['post_status'], $data['post_type'], $data['post_parent'] );
		return $data;
	}

	protected function generate_title( array $data, array $postarr ): string {
		return $data['post_title'];
	}

	protected function generate_slug( array $data, array $postarr ): string {
		return $data['post_name'];
	}

	#[Add_Filter( 'siw_cpt_upload_subdirs' )]
	final public function set_upload_subir( array $subdirs ): array {
		$subdirs[ static::get_post_type() ] = static::get_slug();
		return $subdirs;
	}

	#[Add_Action( 'init', 1 )]
	final public function register_post_types() {
		$count = 0;
		$all_items_suffix = '';
		if ( static::has_active_posts_meta_query() ) {
			// Tel het aantal actieve posts
			$active_posts = get_posts(
				[
					'post_type'  => static::get_post_type(),
					'meta_query' => [ static::get_active_posts_meta_query() ],
					'limit'      => -1,
					'return'     => 'ids',
				]
			);
			$count = count( $active_posts );
			if ( $count > 0 ) {
				$all_items_suffix = ' <span class="awaiting-mod">' . $count . '</span>';
			}
		}

		$post_type = \register_extended_post_type(
			static::get_post_type(),
			[
				'menu_icon'       => 'dashicons-' . static::get_dashicon(),
				'capability_type' => static::get_post_type_base(),
				'map_meta_cap'    => true,
				'quick_edit'      => false,
				'admin_cols'      => static::get_admin_columns(),
				'archive'         => [
					'nopaging' => true,
				],
				'supports'        => array_map(
					fn( Post_Type_Support $support ): string => $support->value,
					static::get_post_type_supports()
				),
				'site_sortables'  => static::get_site_sortables(),
				'labels'          => [
					/* translators: %1$s is de meervoudsvorm van de CPT*/
					'all_items' => sprintf( __( 'Alle %s', 'siw' ), static::get_plural_label() ) . $all_items_suffix,
				],
			],
			[
				'singular' => static::get_singular_label(),
				'plural'   => static::get_plural_label(),
				'slug'     => static::get_slug(),
			]
		);
		foreach ( $this->get_taxonomies() as $taxonomy => $settings ) {
			$post_type->add_taxonomy(
				static::get_post_type() . '_' . $taxonomy,
				$settings['args'] ?? [],
				$settings['names']
			);
		}
	}

	#[Add_Filter( 'mb_settings_pages' )]
	final public function add_settings_page( array $settings_pages ): array {
		$settings_pages[] = [
			'option_name'   => SIW_OPTIONS_KEY,
			'id'            => "{$this::get_post_type()}_settings",
			'menu_title'    => __( 'Instellingen', 'siw' ),
			'capability'    => "edit_{$this::get_post_type_base()}s",
			'submit_button' => __( 'Opslaan', 'siw' ),
			'message'       => __( 'Instellingen opgeslagen', 'siw' ),
			'columns'       => 1,
			'parent'        => 'edit.php?post_type=' . static::get_post_type(),
		];

		return $settings_pages;
	}

	final protected static function get_option( string $option, mixed $default_value = null ): mixed {
		return Meta_Box::get_option( static::get_post_type_base() . '.' . $option, $default_value );
	}

	#[Add_Filter( 'rwmb_meta_boxes' )]
	final public function add_settings_page_meta_box( array $meta_boxes ): array {
		$fields = [
			[
				'id'     => 'archive',
				'type'   => 'group',
				'fields' => [
					[
						'type' => 'heading',
						'name' => __( 'Archiefpagina', 'siw' ),
					],
					[
						'id'       => 'intro',
						'name'     => __( 'Introtekst', 'siw' ),
						'type'     => 'wysiwyg',
						'required' => true,
					],
					[
						'id'      => 'column_count',
						'name'    => __( 'Aantal kolommen', 'siw' ),
						'type'    => 'button_group',
						'options' => [
							100 => '1',
							50  => '2',
							33  => '3',
							25  => '4',
						],
						'std'     => 50,
					],
					[
						'id'        => 'masonry',
						'name'      => __( 'Masonry', 'siw' ),
						'type'      => 'switch',
						'on_label'  => __( 'Ja', 'siw' ),
						'off_label' => __( 'Nee', 'siw' ),
						'visible'   => [
							'when' => [
								[ 'column_count', '!=', 100 ],
							],
						],

					],
				],
			],
			[
				'id'     => 'single',
				'type'   => 'group',
				'fields' => [
					[
						'type' => 'heading',
						'name' => __( 'Individuele post', 'siw' ),
					],
					[
						'id'               => 'fallback_image',
						'name'             => __( 'Terugval-afbeelding', 'siw' ),
						'type'             => 'image_advanced',
						'required'         => true,
						'force_delete'     => false,
						'max_file_uploads' => 1,
						'max_status'       => false,
						'image_size'       => 'thumbnail',
					],
				],
			],
			...static::get_settings_fields(),
		];

		$meta_boxes[] = [
			'id'             => "siw_{$this::get_post_type_base()}_settings",
			'title'          => __( 'Instellingen', 'siw' ),
			'settings_pages' => "siw_{$this::get_post_type_base()}_settings",
			'fields'         => [
				[
					'id'     => static::get_post_type_base(),
					'type'   => 'group',
					'fields' => $fields,
				],
			],
			'toggle_type'    => 'slide',
		];

		return $meta_boxes;
	}

	#[Add_Filter( 'rwmb_meta_boxes' )]
	final public function add_post_type_meta_box( array $meta_boxes ): array {
		$fields = static::get_meta_box_fields();
		if ( ! empty( $this->get_taxonomies() ) ) {
			$taxonomy_fields = [
				[
					'name' => __( 'Categorieën', 'siw' ),
					'type' => 'heading',
				],
			];

			foreach ( $this->get_taxonomies() as $taxonomy => $settings ) {
				$taxonomy_fields[] = [
					'id'             => "{$this::get_post_type()}_{$taxonomy}",
					'name'           => $settings['names']['singular'],
					'type'           => 'taxonomy',
					'required'       => true,
					'remove_default' => true,
					'taxonomy'       => "{$this::get_post_type()}_{$taxonomy}",
					'ajax'           => false,
					'field_type'     => 'radio_list',
				];
			}
			$fields = array_merge( $taxonomy_fields, $fields );
		}

		$meta_boxes[] = [
			'id'          => static::get_post_type(),
			'title'       => static::get_singular_label(),
			'post_types'  => static::get_post_type(),
			'toggle_type' => 'slide',
			'context'     => 'normal',
			'priority'    => 'high',
			'fields'      => $fields,
			'geo'         => [
				'api_key' => Config::get_google_maps_js_api_key(),
				'types'   => [ 'establishment' ],
			],
		];
		return $meta_boxes;
	}

	final protected function is_archive_query( \WP_Query $query = null ): bool {
		if ( null === $query ) {
			global $wp_the_query;
			$query = $wp_the_query;
		}

		if ( is_admin() || false === $query->is_main_query() ) {
			return false;
		}
		if ( $query->is_post_type_archive( static::get_post_type() ) ) {
			return true;
		}
		foreach ( array_keys( $this->get_taxonomies() ) as $taxonomy ) {
			if ( $query->is_tax( "{$this::get_post_type()}_{$taxonomy}" ) ) {
				return true;
			}
		}
		return false;
	}

	#[Add_Action( 'generate_before_main_content', 1 )]
	final public function add_archive_intro() {
		if ( ! $this->is_archive_query() ) {
			return;
		}

		?>
		<div class="siw-intro">
			<?php echo wp_kses_post( static::get_option( 'archive.intro', '' ) ); ?>
		</div>
		<?php

		$taxonomies = array_map(
			fn( string $taxonomy ): string => "{$this->get_post_type()}_{$taxonomy}",
			array_keys( $this->get_taxonomies() )
		);

		if ( ! is_post_type_archive() && is_tax() ) {
			$current_term = get_queried_object();
			$taxonomies = array_diff( $taxonomies, [ $current_term->taxonomy ] );
		}

		if ( 0 === count( $taxonomies ) ) {
			return;
		}

		$taxonomy_filter = Taxonomy_Filter::create();

		$taxonomies = array_map(
			fn( string $taxonomy ): \WP_Taxonomy => get_taxonomy( $taxonomy ),
			$taxonomies
		);

		echo '<div class="flex-container flex-direction-column-mobile">';
		foreach ( $taxonomies as $taxonomy ) {
			printf(
				'<div class="flex-item">%s</div>',
				$taxonomy_filter->set_taxonomy( $taxonomy )->generate() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</div>';
	}

	#[Add_Filter( 'generate_blog_columns' )]
	final public function set_archive_columns( bool $columns ): bool {
		if ( $this->is_archive_query() ) {
			return 100 !== (int) static::get_option( 'archive.column_count', 50 );
		}

		return $columns;
	}

	#[Add_Filter( 'generate_blog_get_column_count' )]
	final public function set_archive_column_count( int $count ): int {
		if ( $this->is_archive_query() ) {
			return (int) static::get_option( 'archive.column_count', 50 );
		}

		return $count;
	}

	#[Add_Filter( 'generate_blog_masonry' )]
	final public function set_archive_masonry( mixed $masonry ): mixed {
		if ( $this->is_archive_query() ) {
			return 100 !== (int) static::get_option( 'archive.column_count', 50 ) && static::get_option( 'archive.masonry', false );
		}

		return $masonry;
	}

	#[Add_Action( 'wp_footer' )]
	final public function add_structured_data(): void {

		if ( ! is_singular( static::get_post_type() ) ) {
			return;
		}

		$structured_data = $this->get_structured_data( get_the_ID() );
		if ( null === $structured_data ) {
			return;
		}
		echo $structured_data->to_script(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	protected function get_structured_data( int $post_id ): ?Thing {
		return null;
	}

	#[Add_Filter( 'get_the_excerpt', 1 )]
	final public function set_excerpt( string $excerpt, \WP_Post $post ): string {

		if ( static::get_post_type() !== get_post_type( $post ) ) {
			return $excerpt;
		}

		$custom_post = $this->get_custom_post( $post );

		return sprintf(
			'%1$s <p><a class="page-link" href="%2$s">%3$s</a></p>',
			$custom_post->get_excerpt(),
			$custom_post->get_permalink(),
			__( 'Lees meer', 'siw' )
		);
	}

	#[Add_Filter( 'the_content' )]
	final public function set_content( string $content ): string {

		if ( ! is_singular( static::get_post_type() ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		return Template::create()
			->set_template( "types/{$this::get_post_type_base()}/single" )
			->set_context( $this->get_template_variables( 'single', get_the_ID() ) )
			->parse_template();
	}

	#[Add_Filter( 'post_thumbnail_id' )]
	final public function set_post_thumbnail_id( int $thumbnail_id, \WP_Post $post ): int {
		if ( get_post_type( $post ) !== static::get_post_type() ) {
			return $thumbnail_id;
		}

		$custom_post = $this->get_custom_post( $post );
		if ( $custom_post->get_thumbnail_id() ) {
			return $custom_post->get_thumbnail_id();
		}

		$fallback_image = static::get_option( 'single.fallback_image' );
		if ( $fallback_image ) {
			$thumbnail_id = $fallback_image[0];
		}

		return $thumbnail_id;
	}
}
