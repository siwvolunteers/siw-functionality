<?php

/**
 * Class om alle functionaliteit van de plugin te laden
 * 
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Autoloader
 */

class SIW_Bootstrap {

	/**
	 * Standaard hook voor initialiseren clas
	 * 
	 * @var string
	 */
	const DEFAULT_HOOK = 'plugins_loaded';

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
		$this->load_woocommerce();

		if ( is_admin() ) {
			$this->load_admin();
		}

		do_action( 'siw_plugin_loaded' );
	}

	/**
	 * Definieer constantes
	 */
	protected function define_constants() {
		define ( 'SIW_PLUGIN_VERSION', '2.1.1' );
		define ( 'SIW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define ( 'SIW_ASSETS_DIR', SIW_PLUGIN_DIR . 'assets' );
		define ( 'SIW_TEMPLATES_DIR', SIW_PLUGIN_DIR . 'templates' );
		define ( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . 'includes' );
		define ( 'SIW_DATA_DIR', SIW_PLUGIN_DIR . 'data' );
		define ( 'SIW_FUNCTIONS_DIR', SIW_INCLUDES_DIR . '/functions' );
		define ( 'SIW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define ( 'SIW_ASSETS_URL', SIW_PLUGIN_URL . 'assets/' );
		define ( 'SIW_SITE_URL', get_home_url() );
		define ( 'SIW_SITE_NAME', wp_parse_url( SIW_SITE_URL )['host'] );
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
		require_once SIW_INCLUDES_DIR . '/class-siw-autoloader.php';
		$autoloader = new SIW_Autoloader();
		$autoloader->set_base_dir( SIW_INCLUDES_DIR );

		$autoloader->add_dir( 'admin', 'Admin' );
		$autoloader->add_dir( 'api', 'API' );
		$autoloader->add_dir( 'batch-jobs', 'Batch_Job' );
		$autoloader->add_dir( 'compatibility', 'Compat' );
		$autoloader->add_dir( 'data', 'Data' );
		$autoloader->add_dir( 'elements', 'Element' );
		$autoloader->add_dir( 'external', 'External' );
		$autoloader->add_dir( 'forms', 'Form' );
		$autoloader->add_dir( 'modules', 'Module' );
		$autoloader->add_dir( 'plato-interface', 'Plato' );
		$autoloader->add_dir( 'woocommerce/admin', 'WC_Admin' );
		$autoloader->add_dir( 'woocommerce/checkout', 'WC_Checkout' );
		$autoloader->add_dir( 'woocommerce/email', 'WC_Email' );
		$autoloader->add_dir( 'woocommerce/import', 'WC_Import' );
		$autoloader->add_dir( 'woocommerce', 'WC' );

		$autoloader->register();
	}

	/**
	 * Laadt functiebestanden
	 */
	protected function load_functions() {
		$functions = [
			'continents',
			'countries',
			'currencies',
			'data',
			'languages',
			'social-networks',
			'work-types',
			//Oud
			'agenda',
			'jobs',
			'quotes',
		];
		foreach ( $functions as $function ) {
			require_once SIW_FUNCTIONS_DIR . "/{$function}.php";
		}

	}

	/**
	 * Laadt kernfunctionaliteit
	 */
	protected function load_core() {
		$this->init_classes( [
			'SIW_Assets',
			'SIW_Head',
			'SIW_htaccess',
			'SIW_i18n',
			'SIW_Icons',
			'SIW_Forms',
			'SIW_Login',
			'SIW_Scheduler',
			'SIW_Shortcodes',
			'SIW_Upload_Dir',
			'SIW_Widgets',
		]);
	}

	/**
	 * Laadt modules
	 */
	protected function load_modules() {
		$this->init_classes( [
			'SIW_Module_Cache_Rebuild',
			'SIW_Module_Cookie_Notice',
			'SIW_Module_Google_Analytics',
			'SIW_Module_Menu_Cart',
			'SIW_Module_Social_Share',
			'SIW_Module_Topbar',
		], 'init' );
	}

	/**
	 * Laadt API-endpoints
	 */
	protected function load_api(){
		$this->init_classes( [
			'SIW_API_Newsletter_Subscribe',
			'SIW_API_Postcode_Lookup'
		]);
	}

	/**
	 * Laadt admin
	 */
	protected function load_admin() {
		$this->init_classes( [
			'SIW_Admin',
			'SIW_Admin_Bar',
			'SIW_Admin_Notices',
			'SIW_Admin_Shortcodes',
			'SIW_Admin_Properties_Page',
		]);
	}

	/**
	 * Laadt compatibiliteit met andere plugins
	 */
	protected function load_compatibility() {
		$this->init_classes( [
			'SIW_Compat',
			'SIW_Compat_Caldera_Forms',
			'SIW_Compat_Mailpoet', 
			'SIW_Compat_Meta_Box',
			'SIW_Compat_Password_Protected',
			'SIW_Compat_Pinnacle_Premium',
			'SIW_Compat_SiteOrigin_Page_Builder',
			'SIW_Compat_The_SEO_Framework',
			'SIW_Compat_UpdraftPlus',
			'SIW_Compat_WooCommerce',
			'SIW_Compat_WordPress',
			'SIW_Compat_WP_Rocket',
			'SIW_Compat_YITH_WCAN',
		]);

		//Aanpassingen voor WP_Sentry moeten eerder geladen worden
		$this->init_class( 'SIW_Compat_WP_Sentry_Integration', 'siw_plugin_loaded' );
	}

	/**
	 * Laadt batch jobs
	 */
	protected function load_batch_jobs() {
		$this->init_classes( [
			'SIW_Batch_Job_Count_Workcamps',
			'SIW_Batch_Job_Delete_Applications',
			'SIW_Batch_Job_Delete_Orphaned_Variations',
			'SIW_Batch_Job_Delete_Workcamps',
			'SIW_Batch_Job_Update_Dutch_Workcamps',
			'SIW_Batch_Job_Update_Free_Places',
			'SIW_Batch_Job_Update_SEO_Noindex',
			'SIW_Batch_Job_Update_Taxonomies',
			'SIW_Batch_Job_Update_Workcamp_Tariffs',
			'SIW_Batch_Job_Update_Workcamp_Visibility',
			'SIW_Batch_Job_Update_Workcamps',

		]);
	}

	/**
	 * Laadt uitbreidingen/aanpassingen voor WooCommerce
	 */
	protected function load_woocommerce() {
		$this->init_classes( [
			'SIW_WC_Admin_Order',
			'SIW_WC_Admin_Product',
			'SIW_WC_Checkout',
			'SIW_WC_Checkout_Discount',
			'SIW_WC_Checkout_Newsletter',
			'SIW_WC_Checkout_Validation',
			'SIW_WC_Emails',
			'SIW_WC_Email_New_Order',
			'SIW_WC_Email_Customer_On_Hold_Order',
			'SIW_WC_Email_Customer_Processing_Order',
		]);
	}


	/**
	 * Laadt classes 
	 *
	 * @param array $classes
	 * @param string $hook
	 */
	protected function init_classes( array $classes, string $hook = self::DEFAULT_HOOK ) {
		foreach ( $classes as $class ) {
			$this->init_class( $class, $hook );
		}
	}

	/**
	 * Laadt 1 class
	 *
	 * @param array $classes
	 * @param string $hook
	 */
	protected function init_class( string $class, string $hook = self::DEFAULT_HOOK ) {
		add_action( $hook, [ $class, 'init' ] );
	}
}
