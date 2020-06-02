<?php

namespace SIW\Core;

use SIW\HTML;
use SIW\Elements;
use SIW\Formatting;
use SIW\Properties;
use SIW\Util;

/**
 * Class voor shortcodes
 * 
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Shortcodes {

	/**
	 * Init
	 * 
	 * @todo logging als functie voor shortcode niet bestaat
	 */
	public static function init() {
		$shortcodes = self::get_shortcodes();

		foreach ( array_keys( $shortcodes ) as $shortcode ) {
			if ( is_callable( __CLASS__ . '::render_' . $shortcode ) ) {
				add_shortcode( "siw_{$shortcode}", __CLASS__ . '::render_' . $shortcode );
			}
		}

		/* Shortcode voor line-break */
		add_shortcode( 'br', function() { return '<br>';});
	}

	/**
	 * Geeft lijst met shortcodes terug
	 *
	 * @return array
	 */
	public static function get_shortcodes(): array {
		$shortcodes = [
			'kvk'                           => __( 'KVK-nummer', 'siw' ),
			'email'                         => __( 'E-mailadres', 'siw' ),
			'email_link'                    => __( 'E-mailadres (link)', 'siw' ),
			'telefoon'                      => __( 'Telefoonnummer', 'siw' ),
			'telefoon_internationaal'       => __( 'Telefoonnummer (internationaal)', 'siw' ),
			'whatsapp'                      => __( 'WhatsApp-nummer', 'siw' ),
			'iban'                          => __( 'IBAN', 'siw' ),
			'openingstijden'                => __( 'Openingstijden', 'siw' ),
			'esc_borg'                      => __( 'ESC-borg', 'siw' ),
			'volgende_infodag'              => __( 'Volgende infodag', 'siw' ),
			'groepsproject_tarief_student'  => __( 'Groepsprojecten - Studententarief', 'siw' ),
			'groepsproject_tarief_regulier' => __( 'Groepsprojecten - Regulier tarief', 'siw' ),
			'op_maat_tarief_student'        => __( 'Op Maat - Studententarief', 'siw' ),
			'op_maat_tarief_regulier'       => __( 'Op Maat - Regulier tarief', 'siw' ),
			'korting_tweede_project'        => __( 'Korting tweede project', 'siw' ),
			'korting_derde_project'         => __( 'Korting derde project', 'siw' ),
			'leeftijd'                      => __( 'Leeftijd van SIW', 'siw' ),
			'laatste_jaarverslag'           => [
				'title'      => __( 'Laatste jaarverslag', 'siw' ),
				'attributes' => [
					[
						'attr'  => 'titel',
						'type'  => 'text',
						'title' => __( 'Titel', 'siw' ),
					],
				]
			],
			'nieuwste_programmaboekje_np' => [
				'title'      => __( 'Nieuwste programmaboekje NP', 'siw' ),
				'attributes' => [
					[
						'attr'  => 'titel',
						'type'  => 'text',
						'title' => __( 'Titel', 'siw' ),
					],
				]
			],
			'externe_link' => [
				'title'      => __( 'Externe link', 'siw' ),
				'attributes' => [
					[
						'attr'  => 'url',
						'type'  => 'text',
						'title' => __( 'Url', 'siw' ),
					],
					[
						'attr'  => 'titel',
						'type'  => 'text',
						'title' => __( 'Titel', 'siw' ),
					],
				],
			],
			'pagina_lightbox' => [
				'title'      => __( 'Pagina-lightbox', 'siw' ),
				'attributes' => [
					[
						'attr'  => 'link_tekst',
						'type'  => 'text',
						'title' => __( 'Linktekst', 'siw' ),
					],
					[
						'attr'    => 'pagina',
						'type'    => 'select',
						'title'   => __( 'Pagina', 'siw' ),
						'options' => [
							'kinderbeleid' => __( 'Beleid kinderprojecten', 'siw' ),
						],
					],
				],
			],
		];
		return $shortcodes;
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
	 * WhatsApp-nummer
	 *
	 * @return string
	 */
	public static function render_whatsapp() {
		return Properties::WHATSAPP;
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
	 * Volgende infodag
	 *
	 * @return string
	 */
	public static function render_volgende_infodag() {
		$info_days = siw_get_upcoming_info_days( 1 );
		if ( empty( $info_days ) ) {
			return __( 'nog niet bekend', 'siw' );
		}
		$date = siw_meta( 'event_date', [], reset( $info_days ) );
		return Formatting::format_date( $date, true );
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
	 * Toont laatste jaarverslag
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function render_laatste_jaarverslag( array $atts ) : string {
		extract( shortcode_atts( [
			'titel' => '',
			], $atts, 'siw_laatste_jaarverslag' )
		);

		$annual_reports = siw_get_option( 'annual_reports' );
		if ( empty( $annual_reports ) ) {
			return '';
		}

		$annual_reports = wp_list_sort( $annual_reports, 'year', 'DESC' );
		$report = reset( $annual_reports );
		$report_url = wp_get_attachment_url( $report['file'][0] );

		//TODO: generate_document_link in HTML
		$report_link = HTML::generate_link(
			$report_url,
			$titel,
			[
				'target'           => '_blank',
				'rel'              => 'noopener',
				'data-ga-track'    => 1,
				'data-ga-type'     => 'event',
				'data-ga-category' => 'Document',
				'data-ga-action'   => 'Downloaden',
				'data-ga-label'    => $report_url,
			]
		);
		return $report_link;
	}

	/**
	 * Toont nieuwste NP-programmboekje
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function render_nieuwste_programmaboekje_np( array $atts ) : string {
		extract( shortcode_atts( [
			'titel' => '',
			], $atts, 'siw_laatste_jaarverslag' )
		);

		$booklets = siw_get_option( 'dutch_projects_booklet' );
		if ( empty( $booklets ) ) {
			return '';
		}

		$booklets = wp_list_sort( $booklets, 'year', 'DESC' );
		$booklet = reset( $booklets );
		$booklet_url = wp_get_attachment_url( $booklet['file'][0] );

		//TODO: generate_document_link in HTML
		$booklet_link = HTML::generate_link(
			$booklet_url,
			$titel,
			[
				'target'           => '_blank',
				'rel'              => 'noopener',
				'data-ga-track'    => 1,
				'data-ga-type'     => 'event',
				'data-ga-category' => 'Document',
				'data-ga-action'   => 'Downloaden',
				'data-ga-label'    => $booklet_url,
			]
		);
		return $booklet_link;
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