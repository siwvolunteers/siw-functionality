<?php declare(strict_types=1);

namespace SIW\Admin;

/**
 * Aanpassingen aan Admin Bar
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Admin_Bar {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'admin_bar_menu', [ $self, 'remove_nodes' ], PHP_INT_MAX );
		add_action( 'admin_bar_menu', [ $self, 'add_logo' ], 1 );
	}

	/** Verwijdert standaardnodes */
	public function remove_nodes( \WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'site-name' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
	}

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
