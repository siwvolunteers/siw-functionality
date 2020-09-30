<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\i18n;

/**
 * Aanpassingen voor WPML
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://wpml.org/
 * @since     3.0.0
 */
class WPML {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		
		add_action( 'widgets_init', [ $self, 'unregister_wpml_widget'], 99 );
		add_action( 'admin_head', [ $self, 'remove_wpml_meta_box'], 99 );
		add_action( 'delete_attachment', [ $self, 'delete_original_attachment' ] );
	}

	/**
	 * Verwijdert WPML widget
	 */
	public function unregister_wpml_widget() {
		unregister_widget( 'WPML_LS_Widget' );
	}

	/**
	 * Verwijdert WPML meta box
	 */
	public function remove_wpml_meta_box() {
		$screen = get_current_screen();
		remove_meta_box( 'icl_div_config', $screen->post_type, 'normal' );
	}

	/**
	 * Verwijder origineel attachment als vertaling verwijderd wordt
	 *
	 * @param int $post_id
	 */
	public function delete_original_attachment( int $post_id ) {
		if ( i18n::is_default_language() ) {
			return;
		}

		$original_post_id = apply_filters( 'wpml_object_id', $post_id, 'attachment', false, i18n::get_default_language() );
		if ( is_int( $original_post_id ) && $post_id !== $original_post_id ) {
			wp_delete_attachment( $original_post_id );
		}
	}
}
