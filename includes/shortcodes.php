<?php declare(strict_types=1);

namespace SIW;

use SIW\Elements\List_Columns;
use SIW\Elements\Modal;
use SIW\Properties;
use SIW\Util;
use SIW\Util\Links;
use SIW\Util\Logger;

/**
 * Class voor shortcodes
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Shortcodes {

	/** Init */
	public static function init() {
		$shortcodes = self::get_shortcodes();

		foreach ( array_keys( $shortcodes ) as $shortcode ) {
			if ( is_callable( __CLASS__ . '::render_' . $shortcode ) ) {
				add_shortcode( "siw_{$shortcode}", __CLASS__ . '::render_' . $shortcode );
			} else {
				Logger::warning( sprintf( 'Shortcode %s heeft geen callback functie.', $shortcode ), __CLASS__ );
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
			'stv_tarief'                    => __( 'STV tarief', 'siw' ),
			'stv_tarief_student'            => __( 'STV tarief inclusief studentenkorting', 'siw' ),
			'mtv_tarief'                    => __( 'MTV tarief', 'siw' ),
			'mtv_tarief_student'            => __( 'MTV tarief inclusief studentenkorting', 'siw' ),
			'ltv_tarief'                    => __( 'LTV tarief', 'siw' ),
			'ltv_tarief_student'            => __( 'LTV tarief inclusief studentenkorting', 'siw' ),
			'np_tarief'                     => __( 'Tarief Nederlandse projecten', 'siw' ),
			'np_tarief_student'             => __( 'Tarief Nederlandse projecten inclusief studentenkorting', 'siw' ),
			'studentenkorting'              => __( 'Studentenkorting', 'siw' ),
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
	public static function render_kvk(): string {
		return Properties::KVK;
	}

	/** E-mailadres */
	public static function render_email(): string {
		return antispambot( Properties::EMAIL );
	}

	/** E-mailadres als mailto-link */
	public static function render_email_link(): string {
		return Links::generate_mailto_link( Properties::EMAIL );
	}

	/** Telefoonnummer */
	public static function render_telefoon(): string {
		return Properties::PHONE;
	}

	/** Internationaal telefoonnummer */
	public static function render_telefoon_internationaal(): string {
		return Properties::PHONE_INTERNATIONAL;
	}

	/** WhatsApp-nummer */
	public static function render_whatsapp(): string {
		return Properties::WHATSAPP;
	}

	/** IBAN */
	public static function render_iban(): string {
		return Properties::IBAN;
	}

	/** Openingstijden */
	public static function render_openingstijden(): string {
		$data = array_map(
			fn( array $value ): string => implode( ': ', $value ),
			siw_get_opening_hours()
		);
		return List_Columns::create()->add_items( $data )->generate();
	}

	/** ESC-borg */
	public static function render_esc_borg(): string {
		return siw_format_amount( Properties::ESC_DEPOSIT );
	}

	/** Volgende infodag */
	public static function render_volgende_infodag(): string {
		$info_days = siw_get_upcoming_info_days( 1 );
		if ( empty( $info_days ) ) {
			return __( 'nog niet bekend', 'siw' );
		}
		$date = siw_meta( 'event_date', [], reset( $info_days ) );
		return siw_format_date( $date, true );
	}

	/** STV tarief */
	public static function render_stv_tarief(): string {
		return siw_format_amount( Properties::STV_PROJECT_FEE );
	}

	/** STV tarief inclusief studentenkorting */
	public static function render_stv_tarief_student(): string {
		return siw_format_amount( Properties::STV_PROJECT_FEE - Properties::STUDENT_DISCOUNT_AMOUNT );
	}

	/** MTV tarief */
	public static function render_mtv_tarief(): string {
		return siw_format_amount( Properties::MTV_PROJECT_FEE );
	}

	/** MTV tarief inclusief studentenkorting */
	public static function render_mtv_tarief_student(): string {
		return siw_format_amount( Properties::MTV_PROJECT_FEE - Properties::STUDENT_DISCOUNT_AMOUNT );
	}

	/** LTV tarief */
	public static function render_ltv_tarief(): string {
		return siw_format_amount( Properties::LTV_PROJECT_FEE );
	}

	/** LTV tarief inclusief studentenkorting */
	public static function render_ltv_tarief_student(): string {
		return siw_format_amount( Properties::LTV_PROJECT_FEE - Properties::STUDENT_DISCOUNT_AMOUNT );
	}

	/** NP tarief */
	public static function render_np_tarief(): string {
		return siw_format_amount( Properties::DUTCH_PROJECT_FEE );
	}

	/** NP tarief inclusief studentenkorting */
	public static function render_np_tarief_student(): string {
		return siw_format_amount( Properties::DUTCH_PROJECT_FEE - Properties::STUDENT_DISCOUNT_AMOUNT );
	}

	/** Studentenkorting */
	public static function render_studentenkorting(): string {
		return siw_format_amount( Properties::STUDENT_DISCOUNT_AMOUNT );
	}

	/** Inschrijfgeld scholenproject */
	public static function render_scholenproject_tarief(): string {
		return siw_format_amount( Properties::SCHOOL_PROJECT_FEE );
	}

	/** Korting tweede Groepsproject */
	public static function render_korting_tweede_project(): string {
		return siw_format_percentage( Properties::DISCOUNT_SECOND_PROJECT );
	}

	/** Externe link */
	public static function render_externe_link( array $atts ): string {
		extract( shortcode_atts( [
			'url'   => '',
			'titel' => '',
			], $atts, 'siw_externe_link' )
		);
		$titel = ( $titel ) ? $titel : $url;

		return Links::generate_external_link( $url, $titel );
	}

	/** Toont laatste jaarverslag */
	public static function render_laatste_jaarverslag( array $atts ): string {
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

	/**
	 * Lightbox met inhoud van pagina
	 * @todo slug als parameter en get page by path gebruiken
	 */
	public static function render_pagina_lightbox( array $atts ): ?string {
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

		return Modal::create()->set_page( (int) $page_id )->generate_link( $link_tekst );
	}

	/** Leeftijd van SIW in jaren */
	public static function render_leeftijd(): string {
		return strval( Util::calculate_age( Properties::FOUNDING_DATE ) );
	}
}
