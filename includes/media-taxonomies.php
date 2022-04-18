<?php declare(strict_types=1);

namespace SIW;

/**
 * Taxonomies voor attachments
 *
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 */
class Media_Taxonomies {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'init', [ $self, 'register_taxonomies'] );
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_metabox'] );
		add_action( 'restrict_manage_posts', [ $self, 'add_taxonomy_filters'] );
	}

	/** Registreert taxonomies voor attachments */
	public function register_taxonomies() {

		$taxonomies = $this->get_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			$labels  = [
				'name'          => $taxonomy['name'],
				'singular_name' => $taxonomy['name'],
			];
			$args = [
				'labels'            => $labels,
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_tagcloud'     => false,
				'show_admin_column' => true,
			];
			register_taxonomy( "siw_attachment_{$taxonomy['slug']}", 'attachment', $args );
		}
	}

	/** Voegt metabox toe */
	public function add_metabox( array $metaboxes ): array {

		$taxonomies = $this->get_taxonomies();
		$fields = [];
		foreach ( $taxonomies as $taxonomy ) {
			$fields[] = [
				'id'             => "siw_attachment_taxonomy_{$taxonomy['slug']}",
				'name'           => $taxonomy['name'],
				'type'           => 'taxonomy',
				'remove_default' => true,
				'taxonomy'       => "siw_attachment_{$taxonomy['slug']}",
				'ajax'           => false,
				'field_type'     => $taxonomy['multiple'] ? 'checkbox_list' : 'select',
			];
		}

		$metaboxes[] = [
			'id'         => 'siw_attachment_taxonomies',
			'title'      => __( 'Media taxonomieën', 'siw' ),
			'post_types' => ['attachment'],
			'context'    => 'side',
			'priority'   => 'low',
			'fields'     => $fields,
		];

		return $metaboxes;
	}

	/**
	 * Geeft taxonomies terug
	 * @todo verplaatsen naar data-bestand?
	 */
	protected function get_taxonomies(): array {

		$taxonomies = [
			[
				'slug'     => 'continent',
				'name'     => __( 'Continent', 'siw' ),
				'multiple' => false,
			],
			[
				'slug'     => 'country',
				'name'     => __( 'Land', 'siw' ),
				'multiple' => false,
			],
			[
				'slug'     => 'work_type',
				'name'     => __( 'Soort werk', 'siw' ),
				'multiple' => true,
			],
		];
		return $taxonomies;
	}

	/** Voegt taxonomy filter toe op admin scherm */
	public function add_taxonomy_filters() {
		$screen = get_current_screen();
		if ( 'upload' != $screen->id ) {
			return;
		}

		$taxonomies = $this->get_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			$args = [
				'taxonomy'        => "siw_attachment_{$taxonomy['slug']}",
				'name'            => "siw_attachment_{$taxonomy['slug']}",
				'value_field'     => 'slug',
				'hide_empty'      => false, //TODO: uitzoeken waarom dit nodig is
				'orderby'         => 'name',
				'show_option_all' => __( 'Alle', 'siw' ),
			];
			wp_dropdown_categories( $args );
		}
	}
}
