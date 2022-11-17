<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Taxonomies as I_Taxonomies;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Voegt meta boxes toe
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Meta_Box extends Base {

	/** Taxonomies */
	protected I_Taxonomies $taxonomies;

	/** Init */
	protected function __construct( protected I_Type $type ) {}

	/** Voegt taxonomies toe */
	public function add_taxonomies( I_Taxonomies $taxonomies ) {
		$this->taxonomies = $taxonomies;
		return $this;
	}

	#[Filter( 'rwmb_meta_boxes' )]
	/** Voegt meta box toe */
	public function add_meta_box( array $meta_boxes ): array {
		$meta_boxes[] = [
			'id'          => $this->type->get_post_type(),
			'title'       => $this->type->get_labels()['singular_name'],
			'post_types'  => $this->type->get_post_type(),
			'toggle_type' => 'slide',
			'context'     => 'normal',
			'priority'    => 'high',
			'fields'      => $this->get_fields(),
			'geo'         => [
				'api_key' => siw_get_option( 'google_maps.api_key' ),
				'types'   => [ 'establishment' ],
			],
		];
		return $meta_boxes;
	}

	/** Haal velden op */
	protected function get_fields(): array {
		$fields = $this->type->get_meta_box_fields();
		if ( isset( $this->taxonomies ) ) {
			$fields = array_merge( $this->get_taxonomy_fields(), $fields );
		}
		return $fields;
	}

	/** Haalt taxonomy-velden op */
	protected function get_taxonomy_fields(): array {
		$taxonomy_fields = [
			[
				'name' => __( 'Categorieën', 'siw' ),
				'type' => 'heading',
			],
		];
		foreach ( $this->taxonomies->get_taxonomies() as $taxonomy => $settings ) {
			$taxonomy_fields[] = [
				'id'             => "siw_{$this->type->get_post_type()}_{$taxonomy}",
				'name'           => $settings['labels']['singular_name'],
				'type'           => 'taxonomy',
				'required'       => true,
				'remove_default' => true,
				'taxonomy'       => "siw_{$this->type->get_post_type()}_{$taxonomy}",
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
