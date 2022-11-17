<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Base;
use SIW\Interfaces\Content\Taxonomies as I_Taxonomies;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Class om taxonomies toe te voegen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Taxonomies extends Base {

	/** Constructor */
	protected function __construct( protected I_Type $type, protected I_Taxonomies $taxonomies ) {}

	/** Registreert taxonomieën */
	public function register_taxonomies() {
		foreach ( $this->taxonomies->get_taxonomies() as $taxonomy => $settings ) {

			$args = [
				'hierarchical'       => false,
				'public'             => true,
				'show_ui'            => true,
				'show_admin_column'  => false,
				'show_in_nav_menus'  => false,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => false,
				'rewrite'            => [
					'slug'         => $settings['slug'],
					'with_front'   => false,
					'hierarchical' => false,
				],
				'labels'             => $settings['labels'],
			];

			register_taxonomy( "{$this->type->get_post_type()}_{$taxonomy}", $this->type->get_post_type(), $args );
		}
	}
}
