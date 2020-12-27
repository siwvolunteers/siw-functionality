<?php declare(strict_types=1);

namespace SIW;

/**
 * SIW Widgets
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Extensions {
	
	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		do_action( 'siw_register_extensions' );

		$extensions = apply_filters( 'siw_extensions', [] );
		foreach ( $extensions as $plugin_file => $extension ) {
			$self->load_extension( $plugin_file, $extension );
		}

		do_action( 'siw_extensions_loaded' );
	}
	
	/**
	 * Laadt 1 extensie
	 *
	 * @param string $plugin_file
	 * @param array $extension
	 */
	protected function load_extension( string $plugin_file, array $extension ) {
		$plugins = get_plugins();
		$plugin_data = $plugins[ $plugin_file ] ?? [];

		$extension_data = [
			'namespace'        => $extension['namespace'],
			'textdomain'       => $plugin_data['TextDomain'],
			'translation_path' => $extension['plugin_path'] . $plugin_data['DomainPath'],
			'src_path'         => $extension['plugin_path'] . $extension['src_dir'],
			'template_path'    => isset( $extension['template_dir'] ) ? $extension['plugin_path'] . $extension['template_dir'] : null,
		];

		new Autoloader( $extension_data['namespace'], $extension_data['src_path']);
		load_plugin_textdomain( $extension_data['textdomain'], false, plugin_basename( $extension_data['translation_path'] ) );

		//Indien van toepassing template directory toevoegen
		if ( null != ( $extension_data['template_path'] ) ) {
			add_filter( 'siw_mustache_template_dirs', fn( array $template_dirs ) : array => array_merge( $template_dirs, [ $extension_data['template_path'] ] ) );
		}
	}
}