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


/*
 * DNS-prefetch voor
 * - Google Analytics
 * - Google Maps
 * - Google Fonts
 */
add_filter( 'wp_resource_hints', function( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$hints[] = '//www.google-analytics.com';
		$hints[] = '//maps.googleapis.com';
		$hints[] = '//maps.google.com';
		$hints[] = '//maps.gstatic.com';
		$hints[] = '//csi.gstatic.com';
		$hints[] = '//fonts.googleapis.com';
		$hints[] = '//fonts.gstatic.com';
	}

	return $hints;
}, 10, 2 );


/*
 * Security headers
 */
add_filter( 'wp_headers', function( $headers ) {
	$headers['X-Frame-Options'] = 'DENY';
	$headers['X-Content-Type-Options'] = 'nosniff';
	$headers['X-XSS-Protection'] = '1; mode=block';
	$headers['Referrer-Policy'] = 'no-referrer-when-downgrade';

	return $headers;
});

/* Verwijderen X-Powered-By header en PHP sessie-cookie httponly maken*/
header_remove('X-Powered-By');
@ini_set('session.cookie_httponly', 'on');

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


/* Diverse UpdraftPlus notificaties verbergen */
define( 'UPDRAFTPLUS_NOADS_B', true );
define( 'UPDRAFTPLUS_NONEWSFEED', true );
define( 'UPDRAFTPLUS_ADMINBAR_DISABLE', true);
define( 'UPDRAFTPLUS_DISABLE_WP_CRON_NOTICE', true );


/* Optimalisatie HEAD */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');


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


/* Query vars registeren voor Search & Filter (Snel zoeken) */
add_filter( 'query_vars', function( $vars ) {
	$vars[] = '_sft_product_cat';
	$vars[] = '_sft_pa_maand';
	return $vars;
} );


/* Parent-pagina's van CPT toevoegen aan breadcrumbs*/
add_action( 'kadence_breadcrumbs_after_home', function() {
	$delimiter = '/';
	$breadcrumb = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="%s"><span itemprop="title">%s</span></a></span> %s ';

	if ( is_singular( 'vacatures' ) ) {
		$vacature_parent = siw_get_setting( 'vacatures_parent_page' );

		/* Afbreken als er geen overzichtspagina is ingesteld*/
		if ( empty( $vacature_parent ) ) {
			return;
		}

		/* Parentpagina's van overzichtspagina */
		$ancestors = array_reverse( get_ancestors( $vacature_parent, 'page') );
		foreach ( $ancestors as $ancestor ) {
			printf( $breadcrumb, get_page_link( $ancestor ), get_the_title( $ancestor ), $delimiter  );
		}
		/* Overzichtspagina */
		printf( $breadcrumb, get_page_link( $vacature_parent ), get_the_title( $vacature_parent ), $delimiter  );

	}
	if ( is_singular( 'agenda' ) ) {
		$agenda_parent = siw_get_setting( 'agenda_parent_page' );

		/* Afbreken als er geen overzichtspagina is ingesteld*/
		if ( empty( $agenda_parent ) ) {
			return;
		}
		/* Parentpagina's van overzichtspagina */
		$ancestors = array_reverse( get_ancestors( $agenda_parent, 'page') );
		foreach ( $ancestors as $ancestor ) {
			printf( $breadcrumb, get_page_link( $ancestor ), get_the_title( $ancestor ), $delimiter  );
		}
		/* Overzichtspagina */
		printf( $breadcrumb, get_page_link( $agenda_parent ), get_the_title( $agenda_parent ), $delimiter  );
	}
} );

/* Sidebar verbergen voor testimonials TODO: Kan weg na switch van Strong Testimonials naar eigen functionaliteit */
add_filter( 'kadence_display_sidebar', function( $sidebar ) {
	if ( 'wpm-testimonial' == get_post_type() ) {
		return false;
	}
	return $sidebar;
} );

/* Knop naar zo-werkt-het pagina onder elk op maat project TODO:pagina uit instelling halen*/
add_action( 'kadence_single_portfolio_value_after', function() {
	?>
	<a href="/zo-werkt-het/projecten-op-maat/" class="kad-btn kad-btn-primary"><?php esc_html_e( 'Alles over projecten op maat','siw' );?></a>
	<?php
} );


/* Functie om categorie header te tonen op productpagina TODO:herschrijven conform naamgevingsconventies */
add_action( 'kt_header_overlay', function() {
	if ( class_exists( 'woocommerce' ) && is_product() ) {
		global $post;
		if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
			$main_term = $terms[0];
			$meta = get_option( 'product_cat_pageheader' );
			if ( empty( $meta ) ) $meta = array();
			if ( ! is_array( $meta ) ) $meta = (array) $meta;
			$meta = isset( $meta[ $main_term->term_id ] ) ? $meta[ $main_term->term_id ] : array();
			if( isset( $meta['kad_pagetitle_bg_image'] ) ) {
				$bg_image_array = $meta['kad_pagetitle_bg_image']; $src = wp_get_attachment_image_src( $bg_image_array[0], 'full' ); $bg_image = $src[0];
				echo '<div class="kt_woo_single_override" style="background:url( ' . $bg_image . ' );"></div>';
			}
		}
	}
} );


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
