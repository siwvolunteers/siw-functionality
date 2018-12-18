<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassing voor Pinnacle Premium
 *
 * @package   SIW\Compatibility
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Formatting
 */

class SIW_Pinnacle_Premium { 

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {

		$self = new self();
		add_filter( 'kad_lazy_load', [ $self, 'set_lazy_load' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'dequeue_scripts' ], PHP_INT_MAX );
		add_filter( 'redux/pinnacle/field/typography/custom_fonts', [ $self, 'add_system_font' ] );
		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], PHP_INT_MAX );
		add_action( 'admin_init', [ $self, 'remove_user_fields' ] );
		add_action( 'init', [ $self, 'remove_metaboxes' ] );
		add_action( 'admin_bar_menu', [ $self, 'hide_admin_bar_node' ], PHP_INT_MAX );
		add_filter( 'siteorigin_panels_widget_dialog_tabs', [ $self, 'add_widget_tab'] );
		add_filter( 'siteorigin_panels_widgets', [ $self, 'group_pinnacle_widgets' ] );
		add_action( 'kadence_single_portfolio_value_after', [ $self, 'add_tailor_made_page_button' ] );
		add_action( 'kt_header_overlay', [ $self, 'show_category_image_on_product_page'] );
		add_filter( 'kadence_display_sidebar', [ $self, 'set_sitebar_visibility'] );
		add_filter( 'kadence_sidebar_id', [ $self, 'set_sitebar_id' ] );
		add_filter( 'the_seo_framework_supported_post_type', [ $self, 'add_portfolio_tsf_support' ], 10, 2 );
		add_action( 'pinnale_breadcrumbs_after_home', [ $self, 'add_breadcrumbs' ] );
		add_action( 'init', [ $self, 'remove_wc_sales_badge' ], PHP_INT_MAX );
		add_shortcode( 'siw_footer', __CLASS__ . '::footer_shortcode' );

		$self->set_permalink_slugs();
		$self->set_capabilities();
	}
		
	/**
	 * Onderdrukt Kadence lazy load
	 *
	 * @param bool $lazy
	 * @return bool
	 */
	public function set_lazy_load( $lazy ) {
		if ( defined( 'DONOTROCKETOPTIMIZE' ) && DONOTROCKETOPTIMIZE ) {
			$lazy = false;
		}
		return $lazy;
	}

	/**
	 * Laadt script alleen op product-pagina
	 *
	 * @return void
	 */
	public function dequeue_scripts() {
		if ( ! is_product() ) {
			wp_dequeue_script( 'kt-wc-add-to-cart-variation-radio' );
		}
	}

	/**
	 * Voegt system font stack toe aan theme options
	 *
	 * @param array $custom_fonts
	 * @return array
	 */
	public function add_system_font( $custom_fonts ) {
		$custom_fonts = [
			"SIW"=> [
				"system-ui" => "System fonts",
			]
		];
		return $custom_fonts;
	}

	/**
	 * Verwijdert Pinnacle widgets
	 *
	 * @return void
	 */
	public function unregister_widgets() {
		unregister_widget( 'kad_contact_widget' );
		unregister_widget( 'kad_social_widget' ); 
		unregister_widget( 'kad_recent_posts_widget' );
		unregister_widget( 'kad_post_grid_widget' );
		unregister_widget( 'kad_gallery_widget' );
		unregister_widget( 'kad_tabs_content_widget' );
	}

	/**
	 * Verwijdert extra gebruikersvelden Pinnacle Premium
	 *
	 * @return void
	 */
	public function remove_user_fields() {
		remove_action( 'show_user_profile', 'kt_show_extra_profile_fields' );
		remove_action( 'edit_user_profile', 'kt_show_extra_profile_fields' );
		remove_action( 'personal_options_update', 'kt_save_extra_profile_fields' );
		remove_action( 'edit_user_profile_update', 'kt_save_extra_profile_fields' );
	}

	/**
	 * Verwijdert extra meta-boxes
	 *
	 * @return void
	 */
	public function remove_metaboxes() {
		remove_filter( 'cmb2_admin_init', 'pinnacle_page_metaboxes' );
		remove_filter( 'cmb2_admin_init', 'pinnacle_postheader_metaboxes' );
		remove_filter( 'cmb2_admin_init', 'pinnacle_product_metaboxes');
		remove_filter( 'cmb2_admin_init', 'pinnacle_productvideo_metaboxes');
		remove_filter( 'cmb2_admin_init', 'pinnacle_product_tab_metaboxes');
		add_filter( 'cmb2_admin_init', function() {
			$page_metabox = cmb2_get_metabox( 'page_title_metabox_options' );
			if ( is_a( $page_metabox, 'CMB2' ) ) {
				$page_metabox->set_prop('closed', true);
			}
		});
	}

	/**
	 * Past permalinkbase aan voor Portfolio en Staff
	 * 
	 * - Portfolio type
	 * - Portfolio tag
	 * - Staff
	 * - Staff group
	 * @return void
	 */
	protected function set_permalink_slugs() {
		add_filter( 'kadence_portfolio_type_slug', function() { return 'projecten-op-maat-in'; } );
		add_filter( 'kadence_portfolio_tag_slug', function() { return 'projecten-op-maat-per-tag'; } );
		add_filter( 'kadence_staff_post_slug', function() { return 'vrijwilligers'; } );
		add_filter( 'kadence_staff_group_slug', function() { return 'vrijwilligers-per-groep'; } );
	}

	/**
	 * Voergt custom capabilities toe voor Pinnacle Premium CPT's
	 *
	 * @return void
	 */
	protected function set_capabilities() {
		add_filter( 'kadence_portfolio_capability_type', function() { return 'op_maat_project'; } );
		add_filter( 'kadence_portfolio_map_meta_cap', '__return_true' );
		add_filter( 'kadence_testimonial_capability_type', function() { return 'quote'; } );
		add_filter( 'kadence_testimonial_map_meta_cap', '__return_true' );
		add_filter( 'kadence_staff_capability_type', function() { return 'volunteer'; } );
		add_filter( 'kadence_staff_map_meta_cap', '__return_true' );
	}

	/**
	 * Verbert Admin Bar node
	 *
	 * @return void
	 */
	public function hide_admin_bar_node( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'ktoptions' );		
	}

	/**
	 * Voegt SiteOrigin-tab toe voor Pinnacle widgets
	 *
	 * @param array $tabs
	 * @return void
	 */
	public function add_widget_tab( $tabs ) {
		$tabs[] = [
			'title' => __( 'Pinnacle Widgets', 'siw' ),
			'filter' => ['groups' => ['kad'] ],
		];
		return $tabs;
	}

	/**
	 * Voegt Pinnacle widgets toe aan eigen tab
	 *
	 * @param array $widgets
	 * @return array
	 */
	public function group_pinnacle_widgets( $widgets ) {
		foreach ( $widgets as $widget_id => &$widget ) {
			if ( 0 === strpos( $widget_id, 'kad_' ) ) {
				$widget['groups'][] = 'kad';
			}
		}
		return $widgets;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function add_tailor_made_page_button() {
		$op_maat_page_link = siw_get_translated_page_link( siw_get_setting( 'op_maat_page' ) );
		echo SIW_Formatting::generate_link(  $op_maat_page_link, __( 'Alles over Projecten Op Maat', 'siw' ), 'kad-btn kad-btn-primary' );	
	}

	/**
	 * Toont categorie-afbeelding op product-pagina
	 *
	 * @return void
	 */
	public function show_category_image_on_product_page() {
		if ( class_exists( 'woocommerce' ) && is_product() ) {
			global $post;
			if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
				$main_term = $terms[0];
				$meta = get_option( 'product_cat_pageheader' );
				if ( empty( $meta ) ) {
					$meta = [];
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
	}

	/**
	 * Undocumented function
	 *
	 * @param bool $show_sidebar
	 * @return bool
	 */
	public function set_sitebar_visibility( $show_sidebar ) {
		if ( 'wpm-testimonial' == get_post_type() || 'vacatures' == get_post_type() || 'agenda' == get_post_type() ) {
			return false;
		}
		if ( is_tax( 'pa_land' ) || is_tax( 'pa_soort-werk' ) || is_tax( 'pa_doelgroep' ) || is_tax ( 'pa_taal' ) || is_tax( 'pa_maand' ) ) {
			return true;
		}
		return $show_sidebar;
	}

	/**
	 * Undocumented function
	 *
	 * @param string $sidebar_id
	 * @return string
	 */
	public function set_sitebar_id( $sidebar_id ) {
		if ( is_tax( 'pa_land') || is_tax( 'pa_soort-werk' ) || is_tax( 'pa_doelgroep' ) || is_tax( 'pa_taal' ) || is_tax( 'pa_maand' ) ) {
			global $pinnacle;
			$sidebar_id = $pinnacle['shop_cat_sidebar'];
		}
	
		return $sidebar_id;
	}

	/**
	 * Voegt TSF support toe voor Op Maat projecten
	 *
	 * @param string $post_type
	 * @param string $post_type_evaluated
	 * @return string
	 */
	public function add_portfolio_tsf_support( $post_type, $post_type_evaluated ) {
		if ( 'portfolio' === $post_type_evaluated )
		return $post_type_evaluated;
	
		return $post_type;
	}

	/**
	 * Voegt breadcrumbs toe voor
	 * 
	 * - Evenementen
	 * - Vacatures
	 * - WooCommerce attribute archive pages
	 *
	 * @return void
	 */
	public function add_breadcrumbs() {
		$delimiter = apply_filters('kadence_breadcrumb_delimiter', '/');
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
	
	}

	/**
	 * Sales badge op product archive page verwijderen
	 *
	 * @return void
	 */
	public function remove_wc_sales_badge() {
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 5 );
	}

	/**
	 * Geeft copyright-tekst voor footer terug
	 *
	 * @return string
	 */
	public static function footer_shortcode() {
		return sprintf( '&copy; %s %s', current_time( 'Y' ), SIW_Properties::get('name') );
	}

}
