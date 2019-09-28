<?php

/**
 * Class voor shortcodes
 * 
 * @package   SIW
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Formatting
 * @uses      SIW_Properties
 */
class SIW_Shortcodes {

	/**
	 * Init
	 * 
	 * @todo logging als functie voor shortcode niet bestaat
	 */
	public static function init() {
		$shortcodes = siw_get_data( 'shortcodes' );

		foreach ( $shortcodes as $shortcode ) {
			if ( is_callable( __CLASS__ . '::render_' . $shortcode['shortcode'] ) ) {
				add_shortcode( "siw_{$shortcode['shortcode']}", __CLASS__ . '::render_' . $shortcode['shortcode'] );
			}
		}

		/* Shortcode voor line-break */
		add_shortcode( 'br', function() { return '<br>';});
	}

	/**
	 * KVK-nummer
	 *
	 * @return string
	 */
	public static function render_kvk() {
		return SIW_Properties::KVK;
	}

	/**
	 * E-mailadres
	 *
	 * @return string
	 */
	public static function render_email() {
		return antispambot( SIW_Properties::EMAIL );
	}

	/**
	 * E-mailadres als mailto-link
	 *
	 * @return string
	 */
	public static function render_email_link() {
		$email = antispambot( SIW_Properties::EMAIL );
		return SIW_Formatting::generate_link( "mailto:" . $email, $email );
	}

	/**
	 * Telefoonnummer
	 *
	 * @return string
	 */
	public static function render_telefoon() {
		return SIW_Properties::PHONE;
	}

	/**
	 * Internationaal telefoonnummer
	 *
	 * @return string
	 */
	public static function render_telefoon_internationaal() {
		return SIW_Properties::PHONE_INTERNATIONAL;
	}

	/**
	 * IBAN
	 *
	 * @return string
	 */
	public static function render_iban() {
		return SIW_Properties::IBAN;
	}

	/**
	 * RSIN
	 *
	 * @return string
	 */
	public static function render_rsin() {
		return SIW_Properties::RSIN;
	}

	/**
	 * Openingstijden
	 *
	 * @return string
	 */
	public static function render_openingstijden() {
		return sprintf( esc_html__( 'Maandag t/m vrijdag %s - %s', 'siw' ), SIW_Properties::OPENING_TIME, SIW_Properties::CLOSING_TIME );
	}

	/**
	 * ESC-borg
	 *
	 * @return string
	 */
	public static function render_esc_borg() {
		return SIW_Formatting::format_amount( SIW_Properties::ESC_DEPOSIT );
	}

	/**
	 * Volgende ESC-deadline
	 *
	 * @return string
	 */
	public static function render_esc_volgende_deadline() {
		$deadlines = siw_get_option( 'esc_deadlines' );
		$next_evs_deadline = SIW_Formatting::format_date( reset( $deadlines ), true );
		return $next_evs_deadline;
	}

	/**
	 * Volgende ESC-vertrekmaand
	 *
	 * @return string
	 */
	public static function render_esc_volgende_vertrekmoment() {

		$weeks = SIW_Properties::ESC_WEEKS_BEFORE_DEPARTURE;
		$deadlines = siw_get_option( 'esc_deadlines' );
		if ( empty( $deadlines ) ) {
			return;
		}
		$next_evs_departure = strtotime( reset( $deadlines ) ) + ( $weeks * WEEK_IN_SECONDS ) ;
		$next_evs_departure_month = date_i18n( 'F Y',  $next_evs_departure );
		return $next_evs_departure_month;
	}

	/**
	 * Volgende infodag
	 *
	 * @return string
	 */
	public static function render_volgende_infodag() {
		$info_days = siw_get_option( 'info_days');
		if ( empty( $info_days ) ) {
			return;
		}
		$next_info_day = SIW_Formatting::format_date( reset( $info_days ), true );
		return $next_info_day;
	}

	/**
	 * Inschrijfgeld Groepsproject (student)
	 *
	 * @return string
	 */
	public static function render_groepsproject_tarief_student() {
		$output = SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_STUDENT );
		if ( SIW_Util::is_workcamp_sale_active() ) {
			$output = sprintf( '<del>%s</del>&nbsp;<ins>%s</ins>', $output, SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_STUDENT_SALE ) );
		}
		return $output;
	}

	/**
	 * Inschrijfgeld Groepsproject (regulier)
	 *
	 * @return string
	 */
	public static function render_groepsproject_tarief_regulier() {
		$output = SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_REGULAR );
		if ( SIW_Util::is_workcamp_sale_active() ) {
			$output = sprintf( '<del>%s</del>&nbsp;<ins>%s</ins>', $output, SIW_Formatting::format_amount( SIW_Properties::WORKCAMP_FEE_REGULAR_SALE ) );
		}
		return $output;
	}

	/**
	 * Inschrijfgeld Op Maaat-project (student)
	 *
	 * @return string
	 */
	public static function render_op_maat_tarief_student() {
		$output = SIW_Formatting::format_amount( SIW_Properties::TAILOR_MADE_FEE_STUDENT );
		if ( SIW_Util::is_tailor_made_sale_active() ) {
			$output = sprintf( '<del>%s</del>&nbsp;<ins>%s</ins>', $output, SIW_Formatting::format_amount( SIW_Properties::TAILOR_MADE_FEE_STUDENT_SALE ) );
		}
		return $output;
	}
	
	/**
	 * Inschrijfgeld Op Maaat-project (regulier)
	 *
	 * @return string
	 */
	public static function render_op_maat_tarief_regulier() {
		$output = SIW_Formatting::format_amount( SIW_Properties::TAILOR_MADE_FEE_REGULAR );
		if ( SIW_Util::is_tailor_made_sale_active() ) {
			$output = sprintf( '<del>%s</del>&nbsp;<ins>%s</ins>', $output, SIW_Formatting::format_amount( SIW_Properties::TAILOR_MADE_FEE_REGULAR_SALE ) );
		}
		return $output;
	}

	/**
	 * Korting tweede Groepsproject
	 *
	 * @return string
	 */
	public static function render_korting_tweede_project() {
		return SIW_Formatting::format_percentage( SIW_Properties::DISCOUNT_SECOND_PROJECT );
	}

	/**
	 * Korting derde Groepsproject
	 *
	 * @return string
	 */
	public static function render_korting_derde_project() {
		return SIW_Formatting::format_percentage( SIW_Properties::DISCOUNT_THIRD_PROJECT );
	}

	/**
	 * Externe link
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function render_externe_link( array $atts ) {
		extract( shortcode_atts( [
			'url'   => '',
			'titel' => '',
			], $atts, 'siw_externe_link' )
		);
		$titel = ( $titel ) ? $titel : $url;
	
		return SIW_Formatting::generate_external_link( $url, $titel );
	}

	/**
	 * Lightbox met inhoud van pagina
	 *
	 * @param array $atts
	 * @return string
	 * 
	 * @todo slug als parameter en get page by path gebruiken
	 * @todo element van maken
	 */
	public static function render_pagina_lightbox( array $atts ) {
		extract( shortcode_atts( [
			'link_tekst' => '',
			'pagina'     => '',
			], $atts, 'siw_pagina_lightbox' )
		);
	
		$pages = [
			'kinderbeleid' => 'child_policy',
		];
		/* Haal pagina id op en breek af als pagina niet ingesteld is */
		$page_id = siw_get_option( $pages[ $pagina ] . '_page' );
		if ( empty( $page_id ) ) {
			return;
		}
		$page_id = SIW_i18n::get_translated_page_id( $page_id );
	
		/* HTML voor lightbox aan footer toeoegen */
		add_action( 'wp_footer', function() use( $page_id ) {
			echo SIW_Formatting::generate_modal( $page_id );
		});
	
		$link = SIW_Formatting::generate_link(
			'#',
			$link_tekst,
			[ 'data-toggle' => 'modal', 'data-target' => "#siw-page-{$page_id}-modal" ]
		);
		return $link;
	}

	/**
	 * Leeftijd van SIW in jaren
	 * 
	 * @return string
	 */
	public static function render_leeftijd() {
		return SIW_Util::calculate_age( SIW_Properties::FOUNDING_DATE );
	}

}
