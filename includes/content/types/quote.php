<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Data\Country;

/**
 * Evenementen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Quote extends Type {

	/** {@inheritDoc} */
	protected string $post_type = 'quote';

	/** {@inheritDoc} */
	protected bool $public = false;

	/** {@inheritDoc} */
	protected string $menu_icon = 'dashicons-format-quote';

	/** {@inheritDoc} */
	protected string $slug = 'quotes';

	/** {@inheritDoc} */
	public function get_meta_box_fields() : array {
		$meta_box_fields = [
			[
				'type' => 'heading',
				'name' => __( 'Gegevens', 'siw' ),
			],
			[
				'id'       => 'quote',
				'name'     => __( 'Quote', 'siw' ),
				'type'     => 'textarea',
				'required' => true,
				'limit'    => 200,
			],
			[
				'id'       => 'name',
				'name'     => __( 'Naam', 'siw' ),
				'type'     => 'text',
				'required' => true,
			],
			[
				'id'          => 'country',
				'name'        => __( 'Land', 'siw' ),
				'type'        => 'select_advanced',
				'options'     => \siw_get_countries_list( Country::ALL, 'slug' ),
				'required'    => true,
				'placeholder' => __( 'Selecteer een land', 'siw' ),
			],
		];
		return $meta_box_fields;
	}

	/** {@inheritDoc} */
	protected function get_labels() : array {
		$labels = [
			'name'          => __( 'Quotes', 'siw' ),
			'singular_name' => __( 'Quote', 'siw' ),
			'add_new'       => __( 'Nieuwe quote', 'siw' ),
			'add_new_item'  => __( 'Nieuwe quote toevoegen', 'siw' ),
			'edit_item'     => __( 'Quote bewerken', 'siw' ),
			'all_items'     => __( 'Alle quotes', 'siw' ),
			'search_items'  => __( 'Quotes zoeken', 'siw' ),
			'not_found'     => __( 'Geen quotes gevonden', 'siw' ),
		];
		return $labels;
	}

	/** {@inheritDoc} */
	protected function get_taxonomies() : array {
		$taxonomies['continent'] = [
			'labels' => [
				'name'          => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
				'singular_name' => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'     => __( 'Continenten', 'siw' ),
				'all_items'     => __( 'Alle continenten', 'siw' ),
				'add_new_item'  => __( 'Continent toevoegen', 'siw' ),
				'update_item'   => __( 'Continent bijwerken', 'siw' ),
				'view_item'     => __( 'View Item', 'siw' ),
				'search_items'  => __( 'Zoek continenten', 'siw' ),
				'not_found'     => __( 'Geen continenten gevonden', 'siw' ),
			],
			'args' => [
				'public' => false,
			],
		];
		$taxonomies['project_type'] = [
			'labels' => [
				'name'          => _x( 'Projectsoort', 'Taxonomy General Name', 'siw' ),
				'singular_name' => _x( 'Projectsoort', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'     => __( 'Projectsoort', 'siw' ),
				'all_items'     => __( 'Alle projectsoorten', 'siw' ),
				'add_new_item'  => __( 'Projectsoort toevoegen', 'siw' ),
				'update_item'   => __( 'Projectsoort bijwerken', 'siw' ),
				'view_item'     => __( 'Bekijk projectsoort', 'siw' ),
				'search_items'  => __( 'Zoek projectsoorten', 'siw' ),
				'not_found'     => __( 'Geen projectsoorten gevonden', 'siw' ),
			],
			'args' => [
				'public' => false,
			],
		];
		return $taxonomies;
	}

	/** {@inheritDoc} */
	protected function generate_title(array $data, array $postarr): string {
		return sprintf(
			'%s | %s %s',
			$postarr['name'],
			get_term( $postarr['siw_quote_project_type'], 'siw_quote_project_type' )->name,
			siw_get_country( $postarr['country'] )->get_name()
		);
	}
}