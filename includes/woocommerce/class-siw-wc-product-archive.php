<?php

/**
 * Aanpassingen aan overzichtspagina van groepsprojecten
 *
 * @package   SIW\WooCommerce
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Formatting
 * @uses      SIW_i18n
 */

class SIW_WC_Product_Archive {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		add_action( 'woocommerce_after_shop_loop_item_title', [ $self, 'show_project_code_and_dates'] );
		add_filter( 'the_seo_framework_the_archive_title', [ $self, 'set_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_generated_archive_excerpt', [ $self, 'set_seo_description' ], 10, 2 );
		add_action( 'after_page_header', [ $self, 'add_archive_description'] );
		add_filter( 'sidebars_widgets', [ $self, 'hide_current_taxonomy_widget'] );

		add_filter( 'woocommerce_default_catalog_orderby_options', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_catalog_orderby', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_get_catalog_ordering_args', [ $self, 'process_catalog_ordering_args' ] );
		add_filter( 'woocommerce_shortcode_products_query', [ $self, 'process_shortcode_ordering_args'], 10, 2 );

		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 ); //TODO: eventueel alleen als kortingsactie actief is
	}

	/**
	 * Toont projectcode en datums
	 */
	public function show_project_code_and_dates() {
		global $product;
		$duration = SIW_Formatting::format_date_range( $product->get_attribute('startdatum'), $product->get_attribute('einddatum'), false );
		//TODO: inline styling verplaatsen naar css
		echo '<p>' . esc_html( $duration ) . '</p><hr style="margin:5px;">';
		echo '<p style="margin-bottom:5px;"><small>' . esc_html( $product->get_sku() ) . '</small></p>';
	}


	/**
	 * Past de SEO titel aan
	 *
	 * @param string $title
	 * @param WP_Term $term
	 * @return string
	 */
	public function set_seo_title( string $title, $term ) {

		if ( ! is_a( $term, 'WP_Term') ) {
			return $title;
		}

		switch ( $term->taxonomy ) {
			case 'pa_land':
			case 'product_cat':
				$title = sprintf( __( 'Groepsprojecten in %s', 'siw' ), $term->name );
				break;
			case 'pa_doelgroep':
				$title = sprintf( __( 'Groepsprojecten voor %s', 'siw' ), $term->name );
				break;
			case 'pa_taal':
				$title = sprintf( __( 'Groepsprojecten met voertaal %s', 'siw' ), $term->name );
				break;
			case 'pa_soort-werk':
				$title = sprintf( __( 'Groepsprojecten met werk gericht op %s', 'siw' ), strtolower( $term->name ) );
				break;
			case 'pa_maand':
				$title = sprintf( __( 'Groepsprojecten in de maand %s', 'siw' ), $term->name );
				break;
		}
		return $title;
	}

	/**
	 * Past SEO-beschrijving aan
	 *
	 * @param string $description
	 * @param WP_Term $term
	 */
	public function set_seo_description( string $description, $term ) {
		if ( ! is_a( $term, 'WP_Term') ) {
			return $description;
		}
		
		switch ( $term->taxonomy ) {
			case 'pa_land':
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen in %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case 'pa_soort-werk':
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), strtolower( $term->name ) ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
		}

		return $description;
	}

	/**
	 * Toont beschrijving van overzichtspagina
	 *
	 * @todo splitsen
	 */
	public function add_archive_description() {

		if ( is_shop() ) {
			$text =	__( 'Hieronder zie je het beschikbare aanbod Groepsprojecten.', 'siw' );
		}
		elseif ( is_product_category() ) {
			$category_name = get_queried_object()->name;
			$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $category_name . '</b>' );
		}
		elseif ( is_tax( 'pa_land' ) ) {
			$country_name = get_queried_object()->name;
			$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $country_name . '</b>' );
		}
		elseif ( is_tax( 'pa_soort-werk' ) ) {
			$work_type_name = get_queried_object()->name;
			$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op %s.', 'siw' ), '<b>' . strtolower( $work_type_name ) . '</b>' );
		}
		elseif ( is_tax( 'pa_doelgroep' ) ) {
			$target_audience_name = get_queried_object()->name;
			$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten voor de doelgroep %s.', 'siw' ), '<b>' . strtolower( $target_audience_name ) . '</b>' );
		}
		elseif ( is_tax( 'pa_taal' ) ) {
			$language_name = get_queried_object()->name;
			$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met de voertaal %s.', 'siw' ), '<b>' . ucfirst( $language_name ) . '</b>' );
		}
		elseif ( is_tax( 'pa_maand' ) ) {
			$month_name = get_queried_object()->name;
			$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in de maand %s.', 'siw' ), '<b>' . ucfirst( $month_name ) . '</b>' );
		}	
	
		if ( ! isset( $text ) ) {
			return;
		}

		$workcamps_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'workcamps_explanation_page' ) );
		$contact_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'contact_page' ) );
		
		/* Toon algemene uitleg over groepsprojecten */
		$text .= SPACE .
			__( 'Tijdens onze Groepsprojecten ga je samen met een internationale groep vrijwilligers voor 2 á 3 weken aan de slag.', 'siw' ) . SPACE .
			__( 'De projecten hebben vaste begin- en einddata.', 'siw' ) . SPACE .
			sprintf( __( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina <a href="%s">Groepsprojecten</a>.', 'siw' ), esc_url( $workcamps_page_link ) );
	
		/* Toon aankondiging voor nieuwe projecten*/
		if ( siw_get_option( 'workcamp_teaser_text_enabled' )
			&& date('Y-m-d') >= siw_get_option( 'workcamp_teaser_text_start_date' )
			&& date('Y-m-d') <= siw_get_option( 'workcamp_teaser_text_end_date' )
			) {
			$teaser_text_end_year = date( 'Y', strtotime( siw_get_option( 'workcamp_teaser_text_end_date' ) ) );
			$teaser_text_end_month = date_i18n( 'F', strtotime( siw_get_option( 'workcamp_teaser_text_end_date' ) ) );
			$text .= BR2 . sprintf( __( 'Vanaf %s wordt het aanbod aangevuld met honderden nieuwe vrijwilligersprojecten voor %s.', 'siw' ), $teaser_text_end_month, $teaser_text_end_year ). SPACE .
				__( 'Wil je nu al meer weten over de grensverleggende mogelijkheden van SIW?', 'siw' ) . SPACE .
				sprintf( __( '<a href="%s">Bel of mail ons</a> en we denken graag met je mee!', 'siw' ), esc_url( $contact_page_link ) );
		}
	
		/* Toon extra tekst als de kortingsactie actief is */
		if ( SIW_Util::is_workcamp_sale_active() ) {

			$regular = SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_REGULAR );
			$regular_sale = SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_REGULAR_SALE );
			$student = SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_STUDENT );
			$student_sale = SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_STUDENT_SALE );
			$end_date = SIW_Formatting::format_date( siw_get_option( 'workcamp_sale_end_date' ), false );
	
			$text .= BR2 . sprintf( __( 'Meld je nu aan en betaal geen %s maar %s voor je vrijwilligersproject.', 'siw' ), $regular, '<b>'. $regular_sale .'</b>' ) . SPACE .
				__( 'Ben je student of jonger dan 18 jaar?', 'siw' ) . SPACE .
				sprintf( __( 'Dan betaal je in plaats van %s nog maar %s.', 'siw' ), $student, '<b>'. $student_sale .'</b>' ) . BR  .
				'<b>' . __( 'Let op:', 'siw' ) . '</b>' . SPACE .
				sprintf( __( 'Deze actie duurt nog maar t/m %s, dus wees er snel bij.', 'siw' ), $end_date );
		}
	
		?>
		<div class="container">
			<div class="row woo-archive-intro">
				<div class="md-12">
					<?php echo wp_kses_post( $text ); ?>
				</div>
			</div>
		</div>
		
		<?php
	}

	/**
	 * Verbergt AJAX-filter van land, doelgroep of maand op desbetreffende landingspagina
	 *
	 * @param array $sidebars_widgets
	 * @return array
	 */
	public function hide_current_taxonomy_widget( array $sidebars_widgets ) {

		global $wp_query;

		if ( ! isset( $wp_query ) ) {
			return $sidebars_widgets;
		}
		if ( is_tax( 'pa_land' ) || is_tax( 'pa_doelgroep' ) || is_tax( 'pa_maand' ) ) {
		
			global $pinnacle;
			$products_sidebar = $pinnacle['shop_cat_sidebar'];
			$products_widgets = $sidebars_widgets[ $products_sidebar ];
			
			$yith_widgets = get_transient( 'siw_yith_widgets' );
			if ( false == $yith_widgets ) {
				$widgets = get_option( 'widget_yith-woo-ajax-navigation' );
				foreach ( $widgets as $id => $widget ) {
					if ( isset( $widget['attribute'] ) ) {
						$yith_widgets[ 'pa_' . $widget['attribute'] ] = 'yith-woo-ajax-navigation-' . $id;
						set_transient( 'siw_yith_widgets', $yith_widgets, DAY_IN_SECONDS );
					}
				}
			}
	
			$taxonomy_slug = get_queried_object()->taxonomy;		
			$taxonomy_widget = isset( $yith_widgets[ $taxonomy_slug ] ) ? $yith_widgets[ $taxonomy_slug ] : '';
	
			if ( ( $index = array_search( $taxonomy_widget, $sidebars_widgets[ $products_sidebar ] ) ) !== false) {
				unset( $sidebars_widgets[ $products_sidebar ][ $index ] );
			}
	
		}
		return $sidebars_widgets;
	}

	/**
	 * Voegt extra sorteeropties toe voor archive
	 * 
	 * - Willekeurig
	 * - Startdatum
	 * - Land
	 * @param array $options
	 * @return array
	 */
	public function add_catalog_orderby_options( $options ) {
		$options['startdate'] = __( 'Startdatum', 'siw' );
		$options['country']   = __( 'Land', 'siw' );
		$options['random']    = __( 'Willekeurig', 'siw' );
	
		return $options;
	}

	/**
	 * Verwerkt extra sorteeropties voor archive
	 *
	 * @param array $args
	 * @return array
	 */
	public function process_catalog_ordering_args( $args ) {
		$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		switch ( $orderby_value ) {
			case 'random':
				$sort_args['orderby']  = 'rand';
				$sort_args['order']    = '';
				$sort_args['meta_key'] = '';
				break;
			case 'startdate':
				$sort_args['orderby']  = 'meta_value';
				$sort_args['order']    = 'asc';
				$sort_args['meta_key'] = 'start_date';
				break;
			case 'country':
				$sort_args['orderby']  = 'meta_value';
				$sort_args['order']    = 'asc';
				$sort_args['meta_key'] = 'land';
				break;
		}
		return $sort_args;
	}

	/**
	 * Verwerkt extra sorteeropties voor shortcode
	 *
	 * @param array $args
	 * @param array $atts
	 * @return array
	 */
	public function process_shortcode_ordering_args( $args, $atts ) {
		if ( 'random' == $atts['orderby'] ) {
			$args['orderby']  = 'rand';
			$args['order']    = '';
			$args['meta_key'] = '';
		}
		return $args;
	}
}
