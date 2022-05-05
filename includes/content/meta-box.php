<?php declare(strict_types=1);

namespace SIW\Content;

/**
 * Voegt metabox toe bij CPT
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Meta_Box {

	/** Velden van de metabox */
	protected array $fields;

	/** Init */
	public function __construct( protected string $post_type, protected string $title, protected array $taxonomies = [] ) {
		add_filter( 'rwmb_meta_boxes', [ $this, 'add_meta_box' ] );
	}

	/** Voegt meta box toe */
	public function add_meta_box( array $meta_boxes ): array {
		$meta_boxes[] = [
			'id'          => "siw_{$this->post_type}",
			'title'       => $this->title,
			'post_types'  => "siw_{$this->post_type}",
			'toggle_type' => 'slide',
			'context'     => 'normal',
			'priority'    => 'high',
			'fields'      => $this->get_fields(),
			'geo'         => [
				'api_key' => siw_get_option( 'google_maps.api_key' ), // TODO: conditioneel maken?
				'types'   => [ 'establishment' ],
			],
		];
		return $meta_boxes;
	}

	/**
	 * Haal velden op
	 *
	 * @return array
	 */
	protected function get_fields(): array {
		$fields = apply_filters( "siw_{$this->post_type}_meta_box_fields", [] );
		if ( ! empty( $this->taxonomies ) ) {
			$fields = array_merge( $this->get_taxonomy_fields(), $fields );
		}
		return $fields;
	}

	/**
	 * Haalt taxonomy-velden op
	 *
	 * @return array
	 */
	protected function get_taxonomy_fields(): array {
		$taxonomy_fields = [
			[
				'name' => __( 'Categorieën', 'siw' ),
				'type' => 'heading',
			],
		];
		foreach ( $this->taxonomies as $taxonomy => $settings ) {
			$taxonomy_fields[] = [
				'id'             => "siw_{$this->post_type}_{$taxonomy}",
				'name'           => $settings['labels']['singular_name'],
				'type'           => 'taxonomy',
				'required'       => true,
				'remove_default' => true,
				'taxonomy'       => "siw_{$this->post_type}_{$taxonomy}",
				'ajax'           => false,
				'field_type'     => 'radio_list',
				'admin_columns'  => [
					'position'   => 'after title',
					'sort'       => true,
					'filterable' => true,
				],
			];
		}
		return $taxonomy_fields;
	}
}
