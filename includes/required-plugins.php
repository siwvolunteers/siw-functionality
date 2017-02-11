<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once SIW_PLUGIN_DIR . '/assets/tgmpa/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'siw_register_required_plugins' );


add_action( 'after_setup_theme', 'siw_remove_kadence_register_required_plugins', 0 );
function siw_remove_kadence_register_required_plugins() {
	remove_action( 'tgmpa_register', 'kadence_register_required_plugins' );
}

add_filter( 'tgmpa_show_admin_notice_capability', function(){
	return 'install_plugins';
});

/**
 * Benodigde plugins
 */
function siw_register_required_plugins() {
	 
	 
	/*
		- Single Sign-on with Azure Active Directory (aad-sso-wordpress)
	*/
	/*
	$plugins[] = array(
		'name'				=> 'The SEO Framework',
		'slug'				=> 'autodescription',
		'required'			=> false,
		'version'			=> '',
	);	 */
	/*
		- BBQ Pro (bbq-pro)
	*/	
	$plugins[] = array(
		'name'				=> 'Black Studio TinyMCE Widget',
		'slug'				=> 'black-studio-tinymce-widget',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'				=> 'Caldera Forms',
		'slug'				=> 'caldera-forms',
		'required'			=> true,
		'version'			=> '',
	);
	/*
	$plugins[] = array(
		'name'				=> 'CMB2',
		'slug'				=> 'cmb2',
		'required'			=> true,
		'version'			=> '',
	);
	*/
	if ( !WP_DEBUG ){
		$plugins[] = array(
			'name'				=> 'Coming Soon Page & Maintenance Mode by SeedProd',
			'slug'				=> 'coming-soon',
			'required'			=> false,
			'version'			=> '',
		);		
	}
	$plugins[] = array(
		'name'				=> 'Contact Form 7',
		'slug'				=> 'contact-form-7',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'				=> 'Enhanced E-commerce for Woocommerce store',
		'slug'				=> 'enhanced-e-commerce-for-woocommerce-store',
		'required'			=> false,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'				=> 'Enhanced Media Library',
		'slug'				=> 'enhanced-media-library',
		'required'			=> false,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'				=> 'Envato Market',
		'slug'				=> 'envato-market',
		'source'			=> 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
		'required'			=> false,
		'version'			=> '1.0.0-RC2',
		'external_url'		=> 'http://envato.github.io/wp-envato-market/',
	);		
	/*
		- Kadence Slider Premium (kadence-slider)
	*/
	$plugins[] = array(
		'name'				=> 'Limit Login Attempts Reloaded',
		'slug'				=> 'limit-login-attempts-reloaded',
		'required'			=> false,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'				=> 'Mailpoet',
		'slug'				=> 'wysija-newsletters',
		'required'			=> true,
		'version'			=> '',
	);
	/*
		- Mailpoet Premium (mailpoet premium)
		- Mapplic (mapplic)
	*/
	$plugins[] = array(
		'name'				=> 'Members',
		'slug'				=> 'members',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'				=> 'Mollie Payments for WooCommerce',
		'slug'				=> 'mollie-payments-for-woocommerce',
		'required'			=> true,
		'version'			=> '',
	);
	/*
		- Ninja Kick: Contact Form (ninja-contact-form)
	*/
	if ( !WP_DEBUG ){
		$plugins[] = array(
			'name'				=> 'Password Protected',
			'slug'				=> 'password-protected',
			'required'			=> false,
			'version'			=> '',
		);
		$plugins[] = array(
			'name'				=> 'Query Monitor',
			'slug'				=> 'query-monitor',
			'required'			=> false,
			'version'			=> '',
		);
	}
	$plugins[] = array(
		'name'				=> 'Redux Framework',
		'slug'				=> 'redux-framework',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'Regenerate Thumbnails',
		'slug'        		=> 'regenerate-thumbnails',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'Safe Redirect Manager',
		'slug'        		=> 'safe-redirect-manager',
		'required'			=> true,
		'version'			=> '',
	);
	/*
		- Search & Filter Pro	(search-filter-pro)
	*/
	$plugins[] = array(
		'name'        		=> 'Page Builder by SiteOrigin',
		'slug'        		=> 'siteorigin-panels',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'Redux Framework',
		'slug'        		=> 'redux-framework',
		'required'			=> true,
		'version'			=> '',
	);	
	$plugins[] = array(
		'name'        		=> 'Strong Testimonials',
		'slug'        		=> 'strong-testimonials',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'UpdraftPlus - Backup/Restore',
		'slug'        		=> 'updraftplus',
		'required'			=> true,
		'version'			=> '',
	);
	/*
		- VFB Pro (vfb-pro)
	*/
	$plugins[] = array(
		'name'        		=> 'WooCommerce',
		'slug'        		=> 'woocommerce',
		'required'			=> true,
		'version'			=> '',
	);
	/*
		- WooCommerce MultiStep Checkout (woocommerce-multistep-checkout)
		- WP All Import - WooCommerce Add-On Pro (wpai-woocommerce-add-on)
		- WP All Import Pro (wp-all-import-pro)
	*/	
	$plugins[] = array(
		'name'        		=> 'WP Crontrol',
		'slug'        		=> 'wp-crontrol',
		'required'			=> false,
		'version'			=> '',
	);
	/*
		- WP Rocket	(wp-rocket)
	*/
	$plugins[] = array(
		'name'        		=> 'WP-Mail-SMTP',
		'slug'        		=> 'wp-mail-smtp',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'WP Rollback',
		'slug'        		=> 'wp-rollback',
		'required'			=> false,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'WP-Sweep',
		'slug'        		=> 'wp-sweep',
		'required'			=> false,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'YITH WooCommerce Ajax Product Filter',
		'slug'        		=> 'yith-woocommerce-ajax-navigation',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'        		=> 'Yoast SEO',
		'slug'        		=> 'wordpress-seo',
		'required'			=> true,
		'version'			=> '2.3.5',
	);


	//Configuratie
	$config = array(
		'id'           => 'siw',
		'default_path' => '',
		'menu'         => 'install-required-plugins',
		'parent_slug'  => 'plugins.php',
		'capability'   => 'install_plugins', 
		'has_notices'  => true,
		'dismissable'  => true,
		'is_automatic' => false,

		/*
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'siw' ),
			'menu_title'                      => __( 'Install Plugins', 'siw' ),
			/* translators: %s: plugin name. * /
			'installing'                      => __( 'Installing Plugin: %s', 'siw' ),
			/* translators: %s: plugin name. * /
			'updating'                        => __( 'Updating Plugin: %s', 'siw' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'siw' ),
			'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'siw'
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'siw'
			),
			'notice_ask_to_update'            => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'siw'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				/* translators: 1: plugin name(s). * /
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'siw'
			),
			'notice_can_activate_required'    => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'siw'
			),
			'notice_can_activate_recommended' => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'siw'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'siw'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'siw'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'siw'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'siw' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'siw' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'siw' ),
			/* translators: 1: plugin name. * /
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'siw' ),
			/* translators: 1: plugin name. * /
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'siw' ),
			/* translators: 1: dashboard link. * /
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'siw' ),
			'dismiss'                         => __( 'Dismiss this notice', 'siw' ),
			'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'siw' ),
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'siw' ),

			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
		),
		*/
	);

	tgmpa( $plugins, $config );
}
