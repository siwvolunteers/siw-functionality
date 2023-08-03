<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\I18n;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

/**
 * Aanpassingen voor WPML
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 * @see       https://wpml.org/
 */
class WPML extends Base implements I_Plugin {

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
	public static function get_plugin_basename(): string {
		return 'sitepress-multilingual-cms/sitepress.php';
	}

	#[Add_Action( 'widgets_init', 99 )]
	/** Verwijdert WPML widget */
	public function unregister_wpml_widget() {
		unregister_widget( \WPML_LS_Widget::class );
	}

	#[Add_Filter( 'privacy_policy_url' )]
	public function set_privacy_policy_url( string $url ): string {
		return I18n::get_translated_permalink( $url, I18n::get_current_language() );
	}

	#[Add_Action( 'members_register_cap_groups' )]
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

	#[Add_Action( 'members_register_caps' )]
	/** Registeert caps */
	public function register_caps() {

		foreach ( self::USER_CAPS as $cap ) {
			\members_register_cap( $cap, [ 'label' => $cap ] );
		}
	}

}
