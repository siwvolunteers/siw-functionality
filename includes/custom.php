<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Klaarzetten acties voor verwerken plugin-update */
add_action( 'wppusher_plugin_was_updated', function() {
	wp_schedule_single_event( time(), 'siw_update_plugin' );
});


/* Aantal toegestane redirects + standaard statuscode aanpassen */
add_filter( 'srm_max_redirects', function() { return 250; } );
add_filter( 'srm_default_direct_status', function() { return 301; } );

/* Nonce-lifetime verdubbelen ivm cache */
add_filter( 'nonce_life', function() { return 2 * DAY_IN_SECONDS; } );


/*
 * Permalink van testimonials aanpassen van 'testimonial' naar 'ervaring'
 * TODO: Kan weg na vervangen plugin "Strong Testimonials" door eigen functionaliteit
 */
add_filter( 'wpmtst_post_type', function( $args ) {
	$args['rewrite']['slug'] = 'ervaring';
	return $args;
} );

/* Samenvatting toevoegen aan pagina's i.v.m. lokale zoekfunctie */
add_action( 'init', function() {
	add_post_type_support( 'page', 'excerpt' );
} );

/* Shortcodes mogelijk maken in text widget */
add_filter( 'widget_text', 'do_shortcode' );


/* PHP sessie-cookie httponly en secure maken*/
@ini_set( 'session.cookie_httponly', 'on' );
@ini_set( 'session.cookie_secure', 'on' );

/* Woocommerce cookie secure maken */
add_filter( 'wc_session_use_secure_cookie', '__return_true' );


/* Mailpoet spam-signups blokkeren */
add_action( 'wp_ajax_nopriv_wysija_ajax', function() {
	$controller = $_POST['controller'];
	$task = $_POST['task'];
	if ( 'subscribers' == $controller && 'save' == $task ) {
		wp_die( '', 403 );
	}
}, 1 );

/* Meerdere nieuwsbrief-aanmeldingen van zelfde IP-adres binnen 1 uur blokkeren (standaard is 1 minuut)*/
add_filter( 'wysija_subscription_limit_base', function() { return HOUR_IN_SECONDS; } );

/*
 * Instellen starttijd Updraft Plus backup
 * - Database
 * - Bestanden
 */
add_filter( 'updraftplus_schedule_firsttime_db', function() {
	$backup_db_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_BACKUP_DB );
	$backup_db_ts_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $backup_db_ts ) ) . ' GMT' );
	return $backup_db_ts_gmt;
} );
add_filter( 'updraftplus_schedule_firsttime_files', function() {
	$backup_files_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_BACKUP_FILES );
	$backup_files_ts_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $backup_files_ts ) ) . ' GMT' );
	return $backup_files_ts_gmt;
} );

/* URL opnemen in bestandsnaam backup */
add_filter( 'updraftplus_blog_name', function( $blog_name ) {
	$blog_name = sanitize_title( SIW_SITE_NAME );
	return $blog_name;
});

/* Diverse UpdraftPlus notificaties verbergen */
define( 'UPDRAFTPLUS_NOADS_B', true );
define( 'UPDRAFTPLUS_NONEWSFEED', true );
define( 'UPDRAFTPLUS_ADMINBAR_DISABLE', true );
define( 'UPDRAFTPLUS_DISABLE_WP_CRON_NOTICE', true );


/* Uitschakelen feed*/
add_actions( array( 'do_feed','do_feed_rdf','do_feed_rss','do_feed_rss2','do_feed_atom','do_feed_rss2_comments','do_feed_atom_comments' ), function () {
	wp_die( __( 'SIW heeft geen feed.', 'siw' ) );
},1);


/* Auteurinfo verwijderen uit oembed */
add_filter( 'oembed_response_data', function( $data ) {
	if ( isset ( $data['author_name'] ) ) {
		unset( $data['author_name'] );
	}
	if ( isset ( $data['author_url'] ) ) {
		unset( $data['author_url'] );
	}
	return $data;
}, 10, 1 );


/**
 * Schrijf informatie naar PHP-log
 *
 * @param mixed $content
 *
 * @return void
 */
function siw_log( $content ) {
	error_log( print_r( $content, true ), 0);
}


/**
 *  Schrijf informatie naar log als DEBUG-mode aan staat
 * @param  mixed $content
 * @return void
 */
function siw_debug_log( $content ) {
	if ( WP_DEBUG ) {
		siw_log( $content );
	}
}


/* Query vars registeren voor Snel Zoeken */
add_filter( 'query_vars', function( $vars ) {
	$vars[] = 'bestemming';
	$vars[] = 'maand';
	return $vars;
} );


/* Parent-pagina's van CPT toevoegen aan breadcrumbs*/
add_action( 'pinnale_breadcrumbs_after_home', function() {
	$delimiter = '/';
	$breadcrumb = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="%s"><span itemprop="title">%s</span></a></span> %s ';

	$parent = '';

	if ( is_singular( 'vacatures' ) ) {
		$parent = siw_get_setting( 'vacatures_parent_page' );
	}
	if ( is_singular( 'agenda' ) ) {
		$parent = siw_get_setting( 'agenda_parent_page' );
	}
	if ( is_singular( 'evs_project' ) ) {
		$parent = siw_get_setting( 'evs_projects_parent_page' );
	}

	/* Breadcrumbs voor attribute-pagina's*/
	if ( is_tax( 'pa_land' ) || is_tax( 'pa_soort-werk' ) || is_tax( 'pa_doelgroep' ) || is_tax ( 'pa_taal' ) ) {
		$parent = wc_get_page_id( 'shop' );
	}

	/* Afbreken als er geen overzichtspagina is ingesteld*/
	if ( empty( $parent ) ) {
		return;
	}

	/* Parentpagina's van overzichtspagina */
	$parent = siw_get_translated_page_id( $parent );
	$ancestors = array_reverse( get_ancestors( $parent, 'page') );
	foreach ( $ancestors as $ancestor ) {
		printf( $breadcrumb, get_page_link( $ancestor ), get_the_title( $ancestor ), $delimiter  );
	}

	/* Overzichtspagina */
	printf( $breadcrumb, get_page_link( $parent ), get_the_title( $parent ), $delimiter  );

} );

/* Sidebar tonen voor product attributes / Sidebar verbergen voor testimonials TODO: Kan weg na switch van Strong Testimonials naar eigen functionaliteit */
add_filter( 'kadence_display_sidebar', function( $sidebar ) {
	if ( 'wpm-testimonial' == get_post_type() ) {
		return false;
	}
	if ( is_tax( 'pa_land' ) || is_tax( 'pa_soort-werk' ) || is_tax( 'pa_doelgroep' ) || is_tax ( 'pa_taal' ) ) {
		return true;
	}
	return $sidebar;
} );

/* Knop naar zo-werkt-het pagina onder elk op maat project */
add_action( 'kadence_single_portfolio_value_after', function() {
	$op_maat_page = siw_get_setting( 'op_maat_page' );
	$op_maat_page = siw_get_translated_page_id( $op_maat_page );
	$op_maat_page_link = get_page_link( $op_maat_page );
	printf( '<a href="%s" class="kad-btn kad-btn-primary">%s</a>', esc_url( $op_maat_page_link ), esc_html__( 'Alles over projecten op maat', 'siw' ) );

} );


/* Functie om categorie header te tonen op productpagina TODO:herschrijven conform naamgevingsconventies */
add_action( 'kt_header_overlay', function() {
	if ( class_exists( 'woocommerce' ) && is_product() ) {
		global $post;
		if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
			$main_term = $terms[0];
			$meta = get_option( 'product_cat_pageheader' );
			if ( empty( $meta ) ) {
				$meta = array();
			}
			if ( ! is_array( $meta ) ) {
				$meta = (array) $meta;
			}
			$meta = isset( $meta[ $main_term->term_id ] ) ? $meta[ $main_term->term_id ] : array();
			if ( isset( $meta['kad_pagetitle_bg_image'] ) ) {
				$bg_image_array = $meta['kad_pagetitle_bg_image'];
				$src = wp_get_attachment_image_src( $bg_image_array[0], 'full' );
				$bg_image = $src[0];
				echo '<div class="kt_woo_single_override" style="background:url( ' . $bg_image . ' );"></div>';
			}
		}
	}
} );

/* WooCommerce help-tab verbergen*/
add_filter( 'woocommerce_enable_admin_help_tab', '__return_false' );

/*
 * Permalinkbase aanpassen voor
 * - Portfolio type
 * - Portfolio tag
 * - Staff
 * - Staff group
 */
add_filter( 'kadence_portfolio_type_slug', function() { return 'projecten-op-maat-in'; } );
add_filter( 'kadence_portfolio_tag_slug', function() { return 'projecten-op-maat-per-tag'; } );
add_filter( 'kadence_staff_post_slug', function() { return 'vrijwilligers'; } );
add_filter( 'kadence_staff_group_slug', function() { return 'vrijwilligers-per-groep'; } );

/* Verwijderen diverse metabox */
add_action( 'init', function() {
	remove_filter( 'cmb2_admin_init', 'pinnacle_page_metaboxes' );
	remove_filter( 'cmb2_admin_init', 'pinnacle_postheader_metaboxes' );
	add_filter( 'cmb2_admin_init', function() {
		$page_metabox = cmb2_get_metabox( 'page_title_metabox_options' );
		if ( is_a( $page_metabox, 'CMB2' ) ) {
			$page_metabox->set_prop('closed', true);
		}
	});
});



/* Fix voor aanpassen van nonce voor logged-out user door WooCommerce*/
add_filter('nonce_user_logged_out', function( $user_id, $action ) {

	$nonces = array(
		'siw_ajax_nonce',
		'siw_newsletter_nonce',
		'caldera_forms_front', //TODO: kan weg na bugfix in CF
		'wp_rest',
	);

	if ( class_exists( 'WooCommerce' ) ) {
		if ( $user_id && 0 !== $user_id && $action && ( false !== strpos_arr( $action, $nonces ) ) ) {
			$user_id = 0;
		}

	}

	return $user_id;

}, 100, 2 );


