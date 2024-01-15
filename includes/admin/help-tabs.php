<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Add_Action;
use SIW\Base;

class Help_Tabs extends Base {

	#[Add_Action( 'admin_head' )]
	public function add_help_tabs() {

		/** @var ?\WP_Screen */
		$screen = get_current_screen();

		if ( null === $screen ) {
			return;
		}

		$post_type = $screen->post_type ?? null;
		$base = $screen->base ?? null;

		if ( null === $post_type || null === $base ) {
			return;
		}

		$help_tab_options = siw_get_option( "{$post_type}_help_tabs.{$base}" );

		if ( ! isset( $help_tab_options['show_help_tabs'] ) || ! $help_tab_options['show_help_tabs'] ) {
			return;
		}

		foreach ( $help_tab_options['help_tabs'] as $help_tab ) {
			$screen->add_help_tab(
				[
					'id'      => wp_unique_prefixed_id( "siw-help-tab-{$post_type}-" ),
					'title'   => esc_html( $help_tab['title'] ),
					'content' => wpautop( wp_kses_post( $help_tab['content'] ) ),
				]
			);
		}
	}
}
