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

	private const USER_CAPS = [
		'wpml_manage_translation_management',
		'wpml_manage_languages',
		'wpml_manage_translation_options',
		'wpml_manage_troubleshooting',
		'wpml_manage_taxonomy_translation',
		'wpml_manage_wp_menus_sync',
		'wpml_manage_translation_analytics',
		'wpml_manage_string_translation',
		'wpml_manage_sticky_links',
		'wpml_manage_navigation',
		'wpml_manage_theme_and_plugin_localization',
		'wpml_manage_media_translation',
		'wpml_manage_support',
		'wpml_manage_woocommerce_multilingual',
		'wpml_operate_woocommerce_multilingual',
	];

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

	#[Action( 'members_register_cap_groups' )]
	/** Registreert cap group */
	public function register_cap_group() {
		\members_register_cap_group(
			'wpml',
			[
				'label'    => 'WPML',
				'icon'     => 'dashicons-translation',
				'priority' => 90,
				'caps'     => self::USER_CAPS,
			]
		);
	}

	#[Action( 'members_register_caps' )]
	/** Registeert caps */
	public function register_caps() {

		foreach ( self::USER_CAPS as $cap ) {
			\members_register_cap( $cap, [ 'label' => $cap ] );
		}
	}

}
