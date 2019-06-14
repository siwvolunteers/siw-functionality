<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class om alle functionaliteit van de plugin te laden
 * 
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_Bootstrap {

	/**
	 * Init
	 */
	public function init() {
		$this->define_constants();
		$this->load_dependencies();
		$this->register_autoloader();

		$this->load_core();
		$this->load_api();
		$this->load_modules();
		$this->load_compatibility();
		$this->load_batch_jobs();

		//if ( is_admin() ) {
			$this->load_admin(); //TODO: conditioneel maken als login niet meer in admin zit
		//}

		//do_action( 'siw_plugin_loaded' );
	}

	/**
	 * Definieer constantes
	 */
	protected function define_constants() {
		define ( 'SIW_PLUGIN_VERSION', '2.1.0' );
		define ( 'SIW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define ( 'SIW_ASSETS_DIR', SIW_PLUGIN_DIR . 'assets' );
		define ( 'SIW_TEMPLATES_DIR', SIW_PLUGIN_DIR . 'templates' );
		define ( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . 'includes' );
		define ( 'SIW_DATA_DIR', SIW_PLUGIN_DIR . 'data' );
		define ( 'SIW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define ( 'SIW_ASSETS_URL', SIW_PLUGIN_URL . 'assets/' );
		define ( 'SIW_SITE_URL', get_home_url() );
		define ( 'SIW_SITE_NAME', wp_parse_url( SIW_SITE_URL )['host'] );
		define ( 'BR', '<br/>' );
		define ( 'BR2', '<br/><br/>' );
		define ( 'SPACE', ' ' );
	}

	/**
	 * Externe libraries laden
	 */
	protected function load_dependencies() {
		require_once( SIW_PLUGIN_DIR . '/vendor/autoload.php' );
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
		$autoloader->add_dir( 'modules', 'Module' );
		$autoloader->add_dir( 'elements', 'Element' );
		$autoloader->add_dir( 'external', 'External' );
		$autoloader->add_dir( 'plato-interface', 'Plato' );

		$autoloader->register();
	}

	/**
	 * Laadt kernfunctionaliteit
	 */
	protected function load_core() {
		$this->init_classes( [
			'SIW_Assets',
			'SIW_i18n',
			'SIW_Icons',
			'SIW_Head',
			'SIW_htaccess',
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
			'SIW_Module_Cookie_Notification',
			'SIW_Module_Google_Analytics',
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
			'SIW_Admin_Login',
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
			'SIW_Batch_Job_Hide_Workcamps',
			'SIW_Batch_Job_Update_Dutch_Workcamps',
			'SIW_Batch_Job_Update_Free_Places',
			//'SIW_Batch_Job_Update_SEO_Noindex',
			'SIW_Batch_Job_Update_Taxonomies',
			'SIW_Batch_Job_Update_Workcamp_Tariffs',
			'SIW_Batch_Job_Update_Workcamps',

		]);
	}


	/**
	 * Laadt classes 
	 *
	 * @param array $classes
	 */
	protected function init_classes( $classes, $hook = 'plugins_loaded' ) {
		$classes = (array) $classes;
		foreach ( $classes as $class ) {
			add_action( $hook, [ $class, 'init' ] );
		}
	}

}
