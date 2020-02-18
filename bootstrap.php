<?php
namespace SIW;
use SIW\Autoloader;

/**
 * Class om alle functionaliteit van de plugin te laden
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Bootstrap {

	/**
	 * Standaard hook voor initialiseren class
	 * 
	 * @var string
	 */
	const DEFAULT_HOOK = 'plugins_loaded';

	/**
	 * Standaard prioriteit voor initialiseren class
	 * 
	 * @var int
	 */
	const DEFAULT_PRIORITY = 10;

	/**
	 * Init
	 */
	public function init() {
		$this->define_constants();
		$this->load_dependencies();
		$this->register_autoloader();
		$this->load_functions();

		$this->load_core();
		$this->load_api();
		$this->load_modules();
		$this->load_compatibility();
		$this->load_batch_jobs();
		$this->load_page_builder();
		$this->load_woocommerce();
		$this->load_content_types();

		if ( is_admin() ) {
			$this->load_admin();
			//$this->load_woocommerce_admin(); //TODO: na splitsen SIW_WC_Order_Admin
		}

		do_action( 'siw_plugin_loaded' );
	}

	/**
	 * Definieer constantes
	 * 
	 * @todo get_plugin_data gebruiken om versienummer te bepalen
	 */
	protected function define_constants() {
		define ( 'SIW_PLUGIN_VERSION', '3.0.0-RC1' );
		define ( 'SIW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define ( 'SIW_ASSETS_DIR', SIW_PLUGIN_DIR . 'assets' );
		define ( 'SIW_TEMPLATES_DIR', SIW_PLUGIN_DIR . 'templates' );
		define ( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . 'includes' );
		define ( 'SIW_DATA_DIR', SIW_PLUGIN_DIR . 'data' );
		define ( 'SIW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define ( 'SIW_ASSETS_URL', SIW_PLUGIN_URL . 'assets/' );
		define ( 'SIW_SITE_URL', get_home_url() );
		define ( 'SIW_SITE_NAME', wp_parse_url( SIW_SITE_URL, PHP_URL_HOST ) );
		define ( 'BR', '<br/>' );
		define ( 'BR2', '<br/><br/>' );
		define ( 'SPACE', ' ' );
		define ( 'HR', '<hr>');
	}

	/**
	 * Externe libraries laden
	 */
	protected function load_dependencies() {
		require_once SIW_PLUGIN_DIR . '/vendor/autoload.php';
	}

	/**
	 * Zet de eigenschappen van de autoloader
	 */
	protected function register_autoloader() {
		require_once SIW_INCLUDES_DIR . '/autoloader.php';
		$autoloader = new Autoloader();
		$autoloader->set_base_dir( SIW_INCLUDES_DIR );
		$autoloader->register();
	}

	/**
	 * Laadt functiebestanden
	 */
	protected function load_functions() {
		$files = glob( SIW_INCLUDES_DIR . '/functions/*.php' );
		foreach ( $files as $file ) {
			require_once $file;
		}
	}

	/**
	 * Laadt kernfunctionaliteit
	 */
	protected function load_core() {
		$this->init_classes(
			'SIW',
			[
				'Animation',
				'Assets',
				'Email\Configuration',
				'Head',
				'htaccess',
				'i18n',
				'Icons',
				'Forms',
				'Login',
				'Media_Taxonomies',
				'Options',
				'Scheduler',
				'Shortcodes',
				'Update',
				'Upload_Subdir',
				'Widgets',
				'Newsletter\Confirmation_Page', //TODO: eigen init??
			]
		);
	}

	/**
	 * Laadt modules
	 */
	protected function load_modules() {
		$this->init_classes(
			'SIW\Modules',
			[
				'Cache_Rebuild',
				'Cookie_Notice',
				'Google_Analytics',
				'Menu_Cart',
				'Social_Share',
				'Topbar',
			],
			'init'
		);
	}

	/**
	 * Laadt API-endpoints
	 */
	protected function load_api() {
		$this->init_classes(
			'SIW\API',
			[
				'Newsletter_Subscribe',
				'Postcode_Lookup'
			]
		);
	}

	/**
	 * Laadt admin
	 */
	protected function load_admin() {
		$this->init_classes(
			'SIW\Admin',
			[
				'Admin',
				'Admin_Bar',
				'Notices',
				'Shortcodes',
				'Options_Page',
				'Properties_Page',
			]
		);
	}

	/**
	 * Laadt compatibiliteit met andere plugins
	 */
	protected function load_compatibility() {
		$this->init_classes(
			'SIW\Compatibility',
			[
				'Caldera_Forms',
				'Mailpoet', 
				'Meta_Box',
				'Password_Protected',
				'Pinnacle_Premium',
				'Plugins',
				'Safe_Redirect_Manager',
				'SiteOrigin_Page_Builder',
				'The_SEO_Framework',
				'UpdraftPlus',
				'WooCommerce',
				'WordPress',
				'WP_Rocket',
				'WPML',
			]
		);

		//Aanpassingen voor WP_Sentry moeten eerder geladen worden
		$this->init_class( 'SIW\Compatibility', 'WP_Sentry_Integration', 'siw_plugin_loaded' );
	}

	/**
	 * Laadt batch jobs
	 */
	protected function load_batch_jobs() {
		$this->init_classes(
			'SIW\Batch',
			[
				'Count_Workcamps',
				'Delete_Applications',
				'Delete_Old_Posts',
				'Delete_Orphaned_Variations',
				'Delete_Workcamps',
				'Update_Dutch_Workcamps',
				'Update_Free_Places',
				'Update_SEO_Noindex',
				'Update_Taxonomies',
				'Update_Workcamp_Tariffs',
				'Update_Workcamp_Visibility',
				'Update_Workcamps',
				'Send_Workcamp_Approval_Emails',
			]
		);
	}

	/**
	 * Laadt uitbreidingen voor SiteOrigin Page Builder
	 */
	protected function load_page_builder() {
		$this->init_classes(
			'SIW\Page_Builder',
			[
				'Animation',
				'Visibility'
			]
		);
	}


	/**
	 * Laadt custom content types
	 */
	protected function load_content_types() {
		
		// Legacy: kan weg na migratie naar content types
		require_once SIW_INCLUDES_DIR . '/content-types/class-siw-post-type.php';
		require_once SIW_INCLUDES_DIR . '/content-types/class-siw-taxonomy.php';
		require_once SIW_INCLUDES_DIR . '/content-types/abstract-siw-content-type.php';
		require_once SIW_INCLUDES_DIR . '/content-types/class-siw-content-type-tm-country.php';
		new \SIW_Content_Type_TM_Country;

		require_once SIW_INCLUDES_DIR . '/post-types/class-siw-post-type-agenda.php';
		$this->init_class( null, 'SIW_Post_Type_Agenda' );

		require_once SIW_INCLUDES_DIR . '/post-types/class-siw-post-type-vacatures.php';
		$this->init_class( null, 'SIW_Post_Type_Vacatures' );
	}

	/**
	 * Laadt uitbreidingen/aanpassingen voor WooCommerce
	 */
	protected function load_woocommerce() {
		$this->init_classes(
			'SIW\Woocommerce',
			[
				'Admin\Coupon',
				'Admin\Order',
				'Admin\Product',
				'Admin\Stockphoto_Page',
				'Checkout\Fields',
				'Checkout\Form',
				'Checkout\Discount',
				'Checkout\Newsletter',
				'Checkout\Terms',
				'Checkout\Validation',
				'Export\Order',
				'Frontend\Product',
				'Frontend\Archive',
				'Email\Emails',
				'Email\New_Order',
				'Email\Customer_On_Hold_Order',
				'Email\Customer_Processing_Order',	
			]
		);
	}

	/**
	 * Laadt uitbreidingen/aanpassingen voor WooCommerce admin
	 */
	protected function load_woocommerce_admin() {
		$this->init_classes(
			'SIW\Woocommerce',
			[
				'Admin\Order',
				'Admin\Product',
			]
		);
	}

	/**
	 * Laadt classes 
	 *
	 * @param string|null $namespace
	 * @param array $classes
	 * @param string $hook
	 * @param int $priority
	 */
	protected function init_classes( ?string $namespace = null, array $classes, string $hook = self::DEFAULT_HOOK, int $priority = self::DEFAULT_PRIORITY ) {
		foreach ( $classes as $class ) {
			$this->init_class( $namespace, $class, $hook, $priority );
		}
	}

	/**
	 * Laadt 1 class
	 *
	 * @param string $namespace
	 * @param string $class
	 * @param string $hook
	 * @param int $priority
	 */
	protected function init_class( string $namespace = null, string $class, string $hook = self::DEFAULT_HOOK, int $priority = self::DEFAULT_PRIORITY ) {
		if ( null !== $namespace ) {
			$class = $namespace . '\\' . $class;
		}

		add_action( $hook, [ $class, 'init' ], $priority );
	}
}
