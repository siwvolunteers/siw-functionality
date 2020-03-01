<?php

namespace SIW;

use SIW\HTML;
use SIW\Elements;
use SIW\Formatting;
use SIW\Properties;

/**
 * Class voor shortcodes
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Shortcodes {

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
		return Properties::KVK;
	}

	/**
	 * E-mailadres
	 *
	 * @return string
	 */
	public static function render_email() {
		return antispambot( Properties::EMAIL );
	}

	/**
	 * E-mailadres als mailto-link
	 *
	 * @return string
	 */
	public static function render_email_link() {
		$email = antispambot( Properties::EMAIL );
		return HTML::generate_link( "mailto:" . $email, $email );
	}

	/**
	 * Telefoonnummer
	 *
	 * @return string
	 */
	public static function render_telefoon() {
		return Properties::PHONE;
	}

	/**
	 * Internationaal telefoonnummer
	 *
	 * @return string
	 */
	public static function render_telefoon_internationaal() {
		return Properties::PHONE_INTERNATIONAL;
	}

	/**
	 * IBAN
	 *
	 * @return string
	 */
	public static function render_iban() {
		return Properties::IBAN;
	}

	/**
	 * RSIN
	 *
	 * @return string
	 */
	public static function render_rsin() {
		return Properties::RSIN;
	}

	/**
	 * Openingstijden
	 *
	 * @return string
	 */
	public static function render_openingstijden() {
		return Elements::generate_opening_hours( 'list' );
	}

	/**
	 * ESC-borg
	 *
	 * @return string
	 */
	public static function render_esc_borg() {
		return Formatting::format_amount( Properties::ESC_DEPOSIT );
	}

	/**
	 * Volgende ESC-deadline
	 *
	 * @return string
	 */
	public static function render_esc_volgende_deadline() {
		$deadlines = siw_get_option( 'esc_deadlines' );
		if ( empty( $deadlines ) ) {
			return;
		}
		return Formatting::format_date( reset( $deadlines ), true );
	}

	/**
	 * Volgende infodag
	 *
	 * @return string
	 */
	public static function render_volgende_infodag() {
		$info_days = siw_get_option( 'info_days' );
		if ( empty( $info_days ) ) {
			return;
		}
		return Formatting::format_date( reset( $info_days ), true );
	}

	/**
	 * Inschrijfgeld Groepsproject (student)
	 *
	 * @return string
	 */
	public static function render_groepsproject_tarief_student() {
		if ( Util::is_workcamp_sale_active() ) {
			return Formatting::format_sale_amount( Properties::WORKCAMP_FEE_STUDENT, Properties::WORKCAMP_FEE_STUDENT_SALE );
		}
		return Formatting::format_amount( Properties::WORKCAMP_FEE_STUDENT );
	}

	/**
	 * Inschrijfgeld Groepsproject (regulier)
	 *
	 * @return string
	 */
	public static function render_groepsproject_tarief_regulier() {
		if ( Util::is_workcamp_sale_active() ) {
			return Formatting::format_sale_amount( Properties::WORKCAMP_FEE_REGULAR, Properties::WORKCAMP_FEE_REGULAR_SALE );
		}
		return Formatting::format_amount( Properties::WORKCAMP_FEE_REGULAR );
	}

	/**
	 * Inschrijfgeld Op Maat-project (student)
	 *
	 * @return string
	 */
	public static function render_op_maat_tarief_student() {
		if ( Util::is_tailor_made_sale_active() ) {
			return Formatting::format_sale_amount( Properties::TAILOR_MADE_FEE_STUDENT, Properties::TAILOR_MADE_FEE_STUDENT_SALE );
		}
		return Formatting::format_amount( Properties::TAILOR_MADE_FEE_STUDENT );
	}
	
	/**
	 * Inschrijfgeld Op Maat-project (regulier)
	 *
	 * @return string
	 */
	public static function render_op_maat_tarief_regulier() {
		if ( Util::is_tailor_made_sale_active() ) {
			return Formatting::format_sale_amount( Properties::TAILOR_MADE_FEE_REGULAR, Properties::TAILOR_MADE_FEE_REGULAR_SALE );
		}
		return Formatting::format_amount( Properties::TAILOR_MADE_FEE_REGULAR );
	}

	/**
	 * Korting tweede Groepsproject
	 *
	 * @return string
	 */
	public static function render_korting_tweede_project() {
		return Formatting::format_percentage( Properties::DISCOUNT_SECOND_PROJECT );
	}

	/**
	 * Korting derde Groepsproject
	 *
	 * @return string
	 */
	public static function render_korting_derde_project() {
		return Formatting::format_percentage( Properties::DISCOUNT_THIRD_PROJECT );
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
	
		return HTML::generate_external_link( $url, $titel );
	}

	/**
	 * Lightbox met inhoud van pagina
	 *
	 * @param array $atts
	 * @return string
	 * 
	 * @todo slug als parameter en get page by path gebruiken
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
		
		return Elements::generate_page_modal( $page_id, $link_tekst );
	}

	/**
	 * Leeftijd van SIW in jaren
	 * 
	 * @return string
	 */
	public static function render_leeftijd() {
		return Util::calculate_age( Properties::FOUNDING_DATE );
	}
}
