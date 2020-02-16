<?php

namespace SIW;

/**
 * Taxonomies voor attachments
 * 
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @since       3.0.0
 */
class Media_Taxonomies {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'init', [ $self, 'register_taxonomies'] );
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_metabox'] );
	}

	/**
	 * Registreert taxonomies voor attachments
	 */
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
				'show_in_menu'      => false,
				'show_tagcloud'     => false,
				'show_admin_column' => false,
			];
			register_taxonomy( "siw_attachment_{$taxonomy['slug']}", 'attachment', $args );
		}
	}

	/**
	 * Voegt metabox toe
	 *
	 * @param array $metaboxes
	 *
	 * @return array
	 */
	public function add_metabox( $metaboxes ) {

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
	 *
	 * @return array
	 * 
	 * @todo verplaatsen naar data-bestand?
	 */
	protected function get_taxonomies() {
		
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
}
