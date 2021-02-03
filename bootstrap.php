<?php declare(strict_types=1);

namespace SIW;
use SIW\Autoloader;

/**
 * Class om alle functionaliteit van de plugin te laden
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Bootstrap {

	/** Standaard hook voor initialiseren class */
	const DEFAULT_HOOK = 'plugins_loaded';

	/** Standaard prioriteit voor initialiseren class */
	const DEFAULT_PRIORITY = 10;

	/** Init */
	public function init() {

		$this->load_extensions();

		$this->define_constants();
		$this->load_dependencies();
		$this->register_autoloader();
		$this->load_textdomain();
		$this->load_functions();

		$this->load_core();
		$this->load_api();
		$this->init_class( 'SIW\Modules', 'Loader', 'init' );

		$this->init_class( 'SIW\Compatibility', 'Loader' );

		$this->load_batch_jobs();
		$this->init_class( 'SIW\Page_Builder', 'Loader' );
		$this->init_class( 'SIW\WooCommerce', 'Loader' );

		$this->load_content_types();

		if ( is_admin() ) {
			$this->init_class( 'SIW\Admin', 'Loader' );
		}

		do_action( 'siw_plugin_loaded' );
	}

	/** Definieer constantes */
	protected function define_constants() {

		$plugin_info = get_file_data( SIW_FUNCTIONALITY_PLUGIN_FILE , [ 'version' => 'Version'] );

		define ( 'SIW_PLUGIN_VERSION', $plugin_info['version'] ); 
		define ( 'SIW_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
		define ( 'SIW_ASSETS_DIR', SIW_PLUGIN_DIR . 'assets/' );
		define ( 'SIW_TEMPLATES_DIR', SIW_PLUGIN_DIR . 'templates/' );
		define ( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . 'includes/' );
		define ( 'SIW_WIDGETS_DIR', SIW_INCLUDES_DIR . 'widgets/' );
		define ( 'SIW_DATA_DIR', SIW_PLUGIN_DIR . 'data/' );
		define ( 'SIW_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
		define ( 'SIW_SITE_URL', get_home_url() );
		define ( 'SIW_SITE_NAME', wp_parse_url( SIW_SITE_URL, PHP_URL_HOST ) );
		define ( 'BR', '<br/>' );
		define ( 'BR2', '<br/><br/>' );
		define ( 'SPACE', ' ' );
		define ( 'HR', '<hr>');
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

	/** Laadt textdomain voor plugin */
	protected function load_textdomain() {
		load_plugin_textdomain( 'siw', false, 'siw-functionality/languages/' );
	}

	/** Laadt functiebestanden */
	protected function load_functions() {
		$files = glob( SIW_INCLUDES_DIR . 'functions/*.php' );
		foreach ( $files as $file ) {
			require_once $file;
		}
	}

	/** Laadt kernfunctionaliteit */
	protected function load_core() {
		$this->init_classes(
			'SIW\Core',
			[
				'Assets',
				'Head',
				'Icons',
				'Login',
				'Media_Taxonomies',
				'Scheduler',
				'Shortcodes',
				'Update',
				'Upload_Subdir',
			]
		);

		$this->init_classes(
			'SIW',
			[
				'Animation',
				'Email\Configuration',
				'Forms',
				'Newsletter\Confirmation_Page',
			]
		);

		$this->init_class( 'SIW\Options', 'Loader' );

		$this->init_class( 'SIW\Widgets', 'Loader' );
	}

	/** Laadt extensies */
	protected function load_extensions() {
		$this->init_class( 'SIW', 'Extensions', 'siw_plugin_loaded' );
	}

	/** Laadt API-endpoints */
	protected function load_api() {
		$this->init_classes(
			'SIW\API',
			[
				'Newsletter_Subscribe',
				'Postcode_Lookup'
			]
		);
	}

	/** Laadt batch jobs */
	protected function load_batch_jobs() {
		$this->init_classes(
			'SIW\Batch',
			[
				'Delete_Applications',
				'Delete_Old_Posts',
				'Import_Dutch_Workcamps',
				'Import_Workcamps',
				'Send_Workcamp_Approval_Emails',
				'Update_Free_Places',
				'Update_Terms',
				'Update_Workcamps',
			]
		);
	}

	/** Laadt custom content types */
	protected function load_content_types() {
		$this->init_classes(
			'SIW\Content\Types',
			[
				'Event',
				'Job_Posting',
				'Quote',
				'Story',
				'TM_Country'
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
