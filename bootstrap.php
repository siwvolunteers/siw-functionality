<?php declare(strict_types=1);

namespace SIW;

use SIW\Assets\SIW_Functionality;
use SIW\Autoloader;

/**
 * Class om alle functionaliteit van de plugin te laden
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Bootstrap {

	/** Standaard hook voor initialiseren class */
	const DEFAULT_HOOK = 'plugins_loaded';

	/** Standaard prioriteit voor initialiseren class */
	const DEFAULT_PRIORITY = 10;

	/** Init */
	public function init() {

		$this->define_constants();
		$this->load_textdomain();
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_plugin_style' ] );

		if ( ! $this->check_requirements() ) {
			add_action( 'admin_notices', [ $this, 'show_requirements_admin_notice' ] );
			return;
		}

		$this->load_dependencies();
		$this->register_autoloader();
		$this->load_functions();

		// Laadt klasses
		$this->init_class( 'SIW', 'Loader' );
		$this->init_loader( 'Actions' );
		$this->init_loader( 'Assets' );
		$this->init_loader( 'Options' );
		$this->init_loader( 'Forms' );
		$this->init_loader( 'Widgets' );
		$this->init_loader( 'Modules', 'init', 11 );
		$this->init_loader( 'Compatibility' );
		$this->init_loader( 'Page_Builder' );
		$this->init_loader( 'WooCommerce' );
		$this->load_content_types();

		if ( is_admin() ) {
			$this->init_loader( 'Admin' );
		}
	}

	/** Definieer constantes */
	protected function define_constants() {

		$plugin_info = get_file_data(
			SIW_FUNCTIONALITY_PLUGIN_FILE,
			[
				'version'         => 'Version',
				'min_php_version' => 'Requires PHP',
				'min_wp_version'  => 'Requires at least',
			]
		);

		define( 'SIW_PLUGIN_VERSION', $plugin_info['version'] );
		define( 'SIW_MIN_PHP_VERSION', $plugin_info['min_php_version'] );
		define( 'SIW_MIN_WP_VERSION', $plugin_info['min_wp_version'] );
		define( 'SIW_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( SIW_FUNCTIONALITY_PLUGIN_FILE ) ) );
		define( 'SIW_ASSETS_DIR', SIW_PLUGIN_DIR . 'assets/' );
		define( 'SIW_TEMPLATES_DIR', SIW_PLUGIN_DIR . 'templates/' );
		define( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . 'includes/' );
		define( 'SIW_WIDGETS_DIR', SIW_INCLUDES_DIR . 'widgets/' );
		define( 'SIW_DATA_DIR', SIW_PLUGIN_DIR . 'data/' );
		define( 'SIW_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
		define( 'SIW_SITE_URL', get_home_url() );
		define( 'SIW_SITE_NAME', wp_parse_url( SIW_SITE_URL, PHP_URL_HOST ) );
		define( 'BR', '<br/>' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
		define( 'BR2', '<br/><br/>' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
		define( 'SPACE', ' ' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
		define( 'HR', '<hr>' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals

		// De log handler moet zo vroeg mogelijk overschreven worden
		define( 'WC_LOG_HANDLER', \WC_Log_Handler_DB::class ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
	}

	/** Controleert requirements */
	protected function check_requirements() {
		return is_wp_version_compatible( SIW_MIN_WP_VERSION ) && is_php_version_compatible( SIW_MIN_PHP_VERSION );
	}

	/** Externe libraries laden */
	protected function load_dependencies() {
		require_once SIW_PLUGIN_DIR . 'vendor/autoload.php';
		require_once SIW_PLUGIN_DIR . 'vendor/woocommerce/action-scheduler/action-scheduler.php';
	}

	/** Registreer autoloaders*/
	protected function register_autoloader() {
		require_once SIW_INCLUDES_DIR . 'autoloader.php';
		new Autoloader( 'SIW', SIW_INCLUDES_DIR );
	}

	/** Toon melding dat minimum requirements niet gehaald zijn is */
	public function show_requirements_admin_notice() {
		$notice = sprintf(
			/* translators: %1$s is WordPress versienummer %2$s is PHP versienummer */
			__( 'De SIW plugin vereist WordPress versie %1$s en PHP versie %2$s', 'siw' ),
			SIW_MIN_WP_VERSION,
			SIW_MIN_PHP_VERSION
		);

		echo '<div class="notice notice-error"><p><b>' . esc_html( $notice ) . '</b></p></div>';
	}

	/** Laadt textdomain voor plugin */
	protected function load_textdomain() {
		load_plugin_textdomain( 'siw', false, 'siw-functionality/languages/' );
	}

	/** Laadt plugin style */
	public function enqueue_plugin_style() {
		wp_enqueue_style( SIW_Functionality::ASSETS_HANDLE );
	}

	/** Laadt functiebestanden */
	protected function load_functions() {
		$files = glob( SIW_INCLUDES_DIR . 'functions/*.php' );
		foreach ( $files as $file ) {
			require_once $file;
		}
	}

	/** Init loader */
	protected function init_loader( string $namespace, string $hook = self::DEFAULT_HOOK, int $priority = self::DEFAULT_PRIORITY ) {
		$this->init_class( "SIW\\{$namespace}", 'Loader', $hook, $priority );
	}

	/** Laadt custom content types */
	protected function load_content_types() {
		$this->init_classes(
			'SIW\Content\Types',
			[
				'Event',
				'Job_Posting',
				'Story',
				'TM_Country',
			]
		);
	}

	/** Laadt classes */
	protected function init_classes( string $namespace, array $classes, string $hook = self::DEFAULT_HOOK, int $priority = self::DEFAULT_PRIORITY ) {
		foreach ( $classes as $class ) {
			$this->init_class( $namespace, $class, $hook, $priority );
		}
	}

	/** Laadt 1 class */
	protected function init_class( string $namespace, string $class, string $hook = self::DEFAULT_HOOK, int $priority = self::DEFAULT_PRIORITY ) {
		add_action( $hook, [ $namespace . '\\' . $class, 'init' ], $priority );
	}
}
