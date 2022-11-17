<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Class om een custom post type toe te voegen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Post_Type extends Base {

	/** {@inheritDoc} */
	protected function __construct( protected I_Type $type ) {}

	#[Action( 'init', 1 )]
	/** Registreert post type */
	public function register_post_type() {
		$args = [
			'label'               => '',
			'description'         => '',
			'labels'              => $this->type->get_labels(),
			'menu_icon'           => $this->type->get_icon(),
			'supports'            => [ 'title' ],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => $this->type->get_slug(),
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => [
				'slug'       => $this->type->get_slug(),
				'with_front' => false,
				'pages'      => false,
				'feeds'      => false,
			],
			// 'capability_type'     => $this->type->get_post_type(),
			// 'map_meta_cap'        => true,
		];

		register_post_type( $this->type->get_post_type(), $args );
	}
}
