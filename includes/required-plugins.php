<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * TGMPA-instantie van Pinnacle Premium verwijderen
*/

add_action( 'after_setup_theme', function() {
	remove_action( 'tgmpa_register', 'kadence_register_required_plugins' );
}, 0 );

/*
 * TGMPA-meldingen alleen tonen aan gebruikers die plugins mogen installeren
*/
add_filter( 'tgmpa_show_admin_notice_capability', function() { return 'install_plugins'; } );

/**
 * Benodigde plugins
 */
add_action( 'tgmpa_register', function() {
	/*
		- Single Sign-on with Azure Active Directory (aad-sso-wordpress)
	*/
	/*
	$plugins[] = array(
		'name'				=> 'The SEO Framework',
		'slug'				=> 'autodescription',
		'source'			=> 'https://github.com/psignoret/aad-sso-wordpress',
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
	$plugins[] = array(
		'name'				=> 'CMB2',
		'slug'				=> 'cmb2',
		'required'			=> true,
		'version'			=> '',
	);
	if ( ! WP_DEBUG ) {
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
	if ( WP_DEBUG ) {
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
		'name'        		=> 'Page Builder by SiteOrigin',
		'slug'        		=> 'siteorigin-panels',
		'required'			=> true,
		'version'			=> '',
	);
	$plugins[] = array(
		'name'				=> 'Redux Framework',
		'slug'				=> 'redux-framework',
		'required'			=> true,
		'version'			=> '',
	);
	/*
	$plugins[] = array(
		'name'        		=> 'Regenerate Thumbnails',
		'slug'        		=> 'regenerate-thumbnails',
		'required'			=> true,
		'version'			=> '',
	);*/
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
	);

	tgmpa( $plugins, $config );
} );
