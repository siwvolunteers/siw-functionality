<?php declare(strict_types=1);

namespace SIW\Core;

use SIW\Elements;
use SIW\Formatting;
use SIW\Properties;
use SIW\Util;
use SIW\Util\Links;

/**
 * Class voor shortcodes
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
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

	/** Geeft lijst met shortcodes terug */
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
			'op_maat_tarief_duo'            => __( 'Op Maat - Duo-tarief', 'siw' ),
			'op_maat_tarief_familie'        => __( 'Op Maat - Familie-tarief', 'siw' ),
			'scholenproject_tarief'         => __( 'Scholenproject - tarief', 'siw' ),
			'korting_tweede_project'        => __( 'Korting tweede project', 'siw' ),
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

	/** KVK-nummer */
	public static function render_kvk() : string {
		return Properties::KVK;
	}

	/** E-mailadres */
	public static function render_email() : string {
		return antispambot( Properties::EMAIL );
	}

	/** E-mailadres als mailto-link */
	public static function render_email_link() : string {
		return Links::generate_mailto_link( Properties::EMAIL );
	}

	/** Telefoonnummer */
	public static function render_telefoon() : string {
		return Properties::PHONE;
	}

	/** Internationaal telefoonnummer */
	public static function render_telefoon_internationaal() : string {
		return Properties::PHONE_INTERNATIONAL;
	}

	/** WhatsApp-nummer */
	public static function render_whatsapp() : string {
		return Properties::WHATSAPP;
	}

	/** IBAN */
	public static function render_iban() : string {
		return Properties::IBAN;
	}

	/** Openingstijden */
	public static function render_openingstijden() : string {
		return Elements::generate_opening_hours( 'list' );
	}

	/** ESC-borg */
	public static function render_esc_borg() : string {
		return Formatting::format_amount( Properties::ESC_DEPOSIT );
	}

	/** Volgende infodag */
	public static function render_volgende_infodag() : string {
		$info_days = siw_get_upcoming_info_days( 1 );
		if ( empty( $info_days ) ) {
			return __( 'nog niet bekend', 'siw' );
		}
		$date = siw_meta( 'event_date', [], reset( $info_days ) );
		return Formatting::format_date( $date, true );
	}

	/** Inschrijfgeld Groepsproject (student) */
	public static function render_groepsproject_tarief_student() : string {
		if ( siw_is_workcamp_sale_active() ) {
			return Formatting::format_sale_amount(
				Properties::WORKCAMP_FEE_STUDENT,
				Properties::WORKCAMP_FEE_STUDENT_SALE
			);
		}
		return Formatting::format_amount( Properties::WORKCAMP_FEE_STUDENT );
	}

	/** Inschrijfgeld Groepsproject (regulier) */
	public static function render_groepsproject_tarief_regulier() : string {
		if ( siw_is_workcamp_sale_active() ) {
			return Formatting::format_sale_amount(
				Properties::WORKCAMP_FEE_REGULAR,
				Properties::WORKCAMP_FEE_REGULAR_SALE
			);
		}
		return Formatting::format_amount( Properties::WORKCAMP_FEE_REGULAR );
	}

	/** Inschrijfgeld Op Maat-project (student) */
	public static function render_op_maat_tarief_student() : string {
		if ( siw_is_tailor_made_sale_active() ) {
			return Formatting::format_sale_amount(
				Properties::TAILOR_MADE_FEE_STUDENT,
				Properties::TAILOR_MADE_FEE_STUDENT_SALE
			);
		}
		return Formatting::format_amount( Properties::TAILOR_MADE_FEE_STUDENT );
	}
	
	/** Inschrijfgeld Op Maat-project (regulier) */
	public static function render_op_maat_tarief_regulier() : string {
		if ( siw_is_tailor_made_sale_active() ) {
			return Formatting::format_sale_amount(
				Properties::TAILOR_MADE_FEE_REGULAR,
				Properties::TAILOR_MADE_FEE_REGULAR_SALE
			);
		}
		return Formatting::format_amount( Properties::TAILOR_MADE_FEE_REGULAR );
	}

	/** Inschrijfgeld Op Maat-project (duo) */
	public static function render_op_maat_tarief_duo() : string {
		return Formatting::format_amount( Properties::TAILOR_MADE_FEE_DUO );
	}

	/** Inschrijfgeld Op Maat-project (familie) */
	public static function render_op_maat_tarief_familie() : string {
		return Formatting::format_amount( Properties::TAILOR_MADE_FEE_FAMILY );
	}

	/** Inschrijfgeld scholenproject */
	public static function render_scholenproject_tarief() : string {
		return Formatting::format_amount( Properties::SCHOOL_PROJECT_FEE );
	}

	/** Korting tweede Groepsproject */
	public static function render_korting_tweede_project() : string {
		return Formatting::format_percentage( Properties::DISCOUNT_SECOND_PROJECT );
	}

	/** Externe link */
	public static function render_externe_link( array $atts ) : string {
		extract( shortcode_atts( [
			'url'   => '',
			'titel' => '',
			], $atts, 'siw_externe_link' )
		);
		$titel = ( $titel ) ? $titel : $url;
	
		return Links::generate_external_link( $url, $titel );
	}

	/** Toont laatste jaarverslag */
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

		return Links::generate_document_link( $report_url, $titel );
	}

	/** Toont nieuwste NP-programmboekje */
	public static function render_nieuwste_programmaboekje_np( array $atts ) : string {
		extract( shortcode_atts( [
			'titel' => '',
			], $atts, 'siw_nieuwste_programmaboekje_np' )
		);

		$booklets = siw_get_option( 'dutch_projects_booklets' );
		if ( empty( $booklets ) ) {
			return '';
		}

		$booklets = wp_list_sort( $booklets, 'year', 'DESC' );
		$booklet = reset( $booklets );
		$booklet_url = wp_get_attachment_url( $booklet['file'][0] );

		return Links::generate_document_link( $booklet_url, $titel );
	}

	/**
	 * Lightbox met inhoud van pagina
	 * @todo slug als parameter en get page by path gebruiken
	 */
	public static function render_pagina_lightbox( array $atts ) : ?string {
		extract( shortcode_atts( [
			'link_tekst' => '',
			'pagina'     => '',
			], $atts, 'siw_pagina_lightbox' )
		);
	
		$pages = [
			'kinderbeleid' => 'child_policy',
		];
		/* Haal pagina id op en breek af als pagina niet ingesteld is */
		$page_id = siw_get_option( "pages.{$pages[$pagina]}" );
		if ( empty( $page_id ) ) {
			return null;
		}
		
		return Elements::generate_page_modal( (int) $page_id, $link_tekst );
	}

	/** Leeftijd van SIW in jaren */
	public static function render_leeftijd() : string {
		return strval( Util::calculate_age( Properties::FOUNDING_DATE ) );
	}
}
