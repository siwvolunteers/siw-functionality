<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Interfaces\Content\Active_Posts as I_Active_Posts;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Voegt teller met actieve posts toe aan admin menu
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Active_Post_Count extends Base {

	/** {@inheritDoc} */
	protected function __construct( protected I_Type $type, protected I_Active_Posts $active_posts ) {}

	#[Action( 'admin_menu', PHP_INT_MAX )]
	/** Toon teller met aantal actieve posts */
	public function add_admin_active_post_count() {
		global $submenu;

		$submenu_index = "edit.php?post_type=siw_{$this->type->get_post_type()}";

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
				'post_type'  => "siw_{$this->type->get_post_type()}",
				'meta_query' => [ $this->active_posts->get_active_posts_meta_query() ],
				'limit'      => -1,
				'return'     => 'ids',
			]
		);

		$count = count( $posts );
		if ( $count > 0 && $menu_item_index ) {
			$submenu[ $submenu_index ][ $menu_item_index ][0] .= ' <span class="awaiting-mod">' . number_format_i18n( $count ) . '</span>'; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}
}
