<?php declare(strict_types=1);

namespace SIW;

use SIW\Attributes\Add_Shortcode;
use SIW\Properties;
use SIW\Util;
use SIW\Util\Links;

/**
 * Class voor shortcodes
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Shortcodes extends Base {

	/** Geeft lijst met shortcodes terug */
	public static function get_shortcodes(): array {
		$shortcodes = [
			'kvk'                         => __( 'KVK-nummer', 'siw' ),
			'email'                       => __( 'E-mailadres', 'siw' ),
			'email_link'                  => __( 'E-mailadres (link)', 'siw' ),
			'telefoon'                    => __( 'Telefoonnummer', 'siw' ),
			'telefoon_internationaal'     => __( 'Telefoonnummer (internationaal)', 'siw' ),
			'whatsapp'                    => __( 'WhatsApp-nummer', 'siw' ),
			'iban'                        => __( 'IBAN', 'siw' ),
			'esc_borg'                    => __( 'ESC-borg', 'siw' ),
			'stv_tarief'                  => __( 'STV tarief', 'siw' ),
			'stv_tarief_student'          => __( 'STV tarief inclusief studentenkorting', 'siw' ),
			'mtv_tarief'                  => __( 'MTV tarief', 'siw' ),
			'mtv_tarief_student'          => __( 'MTV tarief inclusief studentenkorting', 'siw' ),
			'ltv_tarief'                  => __( 'LTV tarief', 'siw' ),
			'ltv_tarief_student'          => __( 'LTV tarief inclusief studentenkorting', 'siw' ),
			'np_tarief'                   => __( 'Tarief Nederlandse projecten', 'siw' ),
			'np_tarief_student'           => __( 'Tarief Nederlandse projecten inclusief studentenkorting', 'siw' ),
			'studentenkorting'            => __( 'Studentenkorting', 'siw' ),
			'scholenproject_tarief'       => __( 'Scholenproject - tarief', 'siw' ),
			'korting_tweede_project'      => __( 'Korting tweede project', 'siw' ),
			'leeftijd'                    => __( 'Leeftijd van SIW', 'siw' ),
			'aantal_vrijwilligers'        => __( 'Aantal vrijwilligers', 'siw' ),
			'aantal_betaalde_medewerkers' => __( 'Aantal betaalde medewerkers', 'siw' ),
			'accent'                      => __( 'Accentkleur', 'siw' ),
			'externe_link'                => [
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
		];

		uasort(
			$shortcodes,
			function( string|array $a, string|array $b ): int {
				$a = is_string( $a ) ? $a : $a['title'];
				$b = is_string( $b ) ? $b : $b['title'];
				return $a <=> $b;
			}
		);
		return $shortcodes;
	}

	/** KVK-nummer */
	#[Add_Shortcode( 'kvk' )]
	public static function render_kvk(): string {
		return Properties::KVK;
	}

	#[Add_Shortcode( 'email' )]
	public static function render_email(): string {
		return antispambot( Properties::EMAIL );
	}

	#[Add_Shortcode( 'email_link' )]
	public static function render_email_link(): string {
		return Links::generate_mailto_link( Properties::EMAIL );
	}

	#[Add_Shortcode( 'telefoon' )]
	public static function render_telefoon(): string {
		return Properties::PHONE;
	}

	#[Add_Shortcode( 'telefoon_internationaal' )]
	public static function render_telefoon_internationaal(): string {
		return Properties::PHONE_INTERNATIONAL;
	}

	#[Add_Shortcode( 'whatsapp' )]
	public static function render_whatsapp(): string {
		return Properties::WHATSAPP;
	}

	#[Add_Shortcode( 'iban' )]
	public static function render_iban(): string {
		return Properties::IBAN;
	}

	#[Add_Shortcode( 'esc_borg' )]
	public static function render_esc_borg(): string {
		return siw_format_amount( Config::get_esc_deposit() );
	}

	#[Add_Shortcode( 'stv_tarief' )]
	public static function render_stv_tarief(): string {
		return siw_format_amount( Config::get_stv_project_fee() );
	}

	#[Add_Shortcode( 'stv_tarief_student' )]
	public static function render_stv_tarief_student(): string {
		return siw_format_amount( Config::get_stv_project_fee() - Config::get_student_discount_amount() );
	}

	#[Add_Shortcode( 'mtv_tarief' )]
	public static function render_mtv_tarief(): string {
		return siw_format_amount( Config::get_mtv_project_fee() );
	}

	#[Add_Shortcode( 'mtv_tarief_student' )]
	public static function render_mtv_tarief_student(): string {
		return siw_format_amount( Config::get_mtv_project_fee() - Config::get_student_discount_amount() );
	}

	#[Add_Shortcode( 'ltv_tarief' )]
	public static function render_ltv_tarief(): string {
		return siw_format_amount( Config::get_ltv_project_fee() );
	}

	#[Add_Shortcode( 'ltv_tarief_student' )]
	public static function render_ltv_tarief_student(): string {
		return siw_format_amount( Config::get_ltv_project_fee() - Config::get_student_discount_amount() );
	}

	#[Add_Shortcode( 'np_tarief' )]
	public static function render_np_tarief(): string {
		return siw_format_amount( Config::get_dutch_project_fee() );
	}

	#[Add_Shortcode( 'np_tarief_student' )]
	public static function render_np_tarief_student(): string {
		return siw_format_amount( Config::get_dutch_project_fee() - Config::get_student_discount_amount() );
	}

	#[Add_Shortcode( 'studentenkorting' )]
	public static function render_studentenkorting(): string {
		return siw_format_amount( Config::get_student_discount_amount() );
	}

	#[Add_Shortcode( 'scholenproject_tarief' )]
	public static function render_scholenproject_tarief(): string {
		return siw_format_amount( Config::get_student_discount_amount() );
	}

	#[Add_Shortcode( 'korting_tweede_project' )]
	public static function render_korting_tweede_project(): string {
		return siw_format_percentage( Config::get_discount_percentage_second_project() );
	}

	#[Add_Shortcode( 'externe_link' )]
	public static function render_externe_link( array $atts ): string {
		extract( // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			shortcode_atts(
				[
					'url'   => '',
					'titel' => '',
				],
				$atts,
				'siw_externe_link'
			)
		);
		$titel = ( $titel ) ? $titel : $url;

		return Links::generate_external_link( $url, $titel );
	}

	#[Add_Shortcode( 'leeftijd' )]
	public static function render_leeftijd(): string {
		return strval( Util::calculate_age( Properties::FOUNDING_DATE ) );
	}

	#[Add_Shortcode( 'aantal_vrijwilligers' )]
	public static function render_aantal_vrijwilligers(): string {
		return siw_get_option( 'staff.number_of_volunteers' );
	}

	#[Add_Shortcode( 'aantal_betaalde_medewerker' )]
	public static function render_aantal_betaalde_medewerkers(): string {
		return siw_get_option( 'staff.number_of_employees' );
	}

	#[Add_Shortcode( 'accent' )]
	public static function render_accent( $atts, string $content ): string {
		return sprintf( '<span class="siw-accent">%s</span>', $content );
	}
}
