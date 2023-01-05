<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\I18n;

/**
 * Aanpassingen voor WPML
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 * @see       https://wpml.org/
 */
class WPML extends Plugin {

	/** {@inheritDoc} */
	protected static function get_plugin_path(): string {
		return 'sitepress-multilingual-cms/sitepress.php';
	}

	#[Action( 'widgets_init', 99 )]
	/** Verwijdert WPML widget */
	public function unregister_wpml_widget() {
		unregister_widget( \WPML_LS_Widget::class );
	}

	#[Action( 'delete_attachment' )]
	/** Verwijder origineel attachment als vertaling verwijderd wordt */
	public function delete_original_attachment( int $post_id ) {
		if ( I18n::is_default_language() ) {
			return;
		}

		$original_post_id = apply_filters( 'wpml_object_id', $post_id, 'attachment', false, I18n::get_default_language() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		if ( is_int( $original_post_id ) && $post_id !== $original_post_id ) {
			wp_delete_attachment( $original_post_id );
		}
	}
}
