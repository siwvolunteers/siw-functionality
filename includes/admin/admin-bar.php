<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Action;
use SIW\Base;

/**
 * Aanpassingen aan Admin Bar
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Admin_Bar extends Base {

	#[Action( 'admin_bar_menu', PHP_INT_MAX )]
	/** Verwijdert standaardnodes */
	public function remove_nodes( \WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'site-name' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
	}

	#[Action( 'admin_bar_menu', 1 )]
	/** Voegt logo toe aan adminbar */
	public function add_logo( \WP_Admin_Bar $wp_admin_bar ) {
		$logo_args = [
			'id'   => 'siw-logo',
			'meta' => [
				'class' => 'siw-logo',
				'title' => 'SIW',
			],
		];
		$wp_admin_bar->add_node( $logo_args );
	}

}
