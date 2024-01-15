<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Add_Action;
use SIW\Base;

class Admin_Bar extends Base {

	#[Add_Action( 'admin_bar_menu', PHP_INT_MAX )]
	public function remove_nodes( \WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'site-name' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
	}

	#[Add_Action( 'admin_bar_menu', 1 )]
	public function add_logo( \WP_Admin_Bar $wp_admin_bar ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( false === $logo_id ) {
			return;
		}

		$logo_html = wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			[
				'style' => 'height: var(--wp-admin--admin-bar--height); filter: brightness(0) invert(1)',
			]
		);

		$logo_args = [
			'id'    => 'siw-logo',
			'title' => sprintf( '<span class="ab-label">%s</span>', $logo_html ),
			'meta'  => [
				'class' => 'siw-logo',
			],
		];
		$wp_admin_bar->add_node( $logo_args );
	}
}
