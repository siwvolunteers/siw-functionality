<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
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
	 */
	public static function init() {
		$shortcodes = array(
			'kvk'                           => 'kvk',
			'email'                         => 'email',
			'email_link'                    => 'email_link',
			'telefoon'                      => 'phone',
			'telefoon_internationaal'       => 'phone_international',
			'iban'                          => 'iban',
			'rsin'                          => 'rsin',
			'openingstijden'                => 'opening_hours',
			'evs_borg'                      => 'esc_deposit',
			'esc_borg'                      => 'esc_deposit',
			'evs_volgende_deadline'         => 'next_esc_deadline',
			'esc_volgende_deadline'         => 'next_esc_deadline',
			'evs_volgende_vertrekmoment'    => 'next_esc_departure_month',
			'esc_volgende_vertrekmoment'    => 'next_esc_departure_month',
			'volgende_infodag'              => 'next_info_day',
			'groepsproject_tarief_student'  => 'workcamp_fee_student',
			'groepsproject_tarief_regulier' => 'workcamp_fee_regular',
			'op_maat_tarief_student'        => 'tailor_made_fee_student',
			'op_maat_tarief_regulier'       => 'tailor_made_fee_regular',
			'korting_tweede_project'        => 'discount_second_project',
			'korting_derde_project'         => 'discount_third_project',
			'externe_link'                  => 'external_link',
			'bestuursleden'                 => 'board_members',
			'jaarverslagen'                 => 'annual_reports',
			'nederlandse_projecten'         => 'dutch_projects',
			'pagina_lightbox'               => 'page_modal',
			'cirkeldiagram'                 => 'pie_chart',
			'leeftijd'                      => 'age',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( "siw_{$shortcode}", __CLASS__ . '::' . $function );
		}

		/* Break */
		add_shortcode( 'br', function() { return '<br>';});
	}

	/**
	 * KVK-nummer
	 *
	 * @return string
	 */
	public static function kvk() {
		return SIW_Properties::get('kvk');
	}

	/**
	 * E-mailadres
	 *
	 * @return string
	 */
	public static function email() {
		return antispambot( SIW_Properties::get('email') );
	}

	/**
	 * E-mailadres als mailto-link
	 *
	 * @return string
	 */
	public static function email_link() {
		$email = antispambot( SIW_Properties::get('email') );
		return SIW_Formatting::generate_link( "mailto:" . $email, $email );
	}

	/**
	 * Telefoonnummer
	 *
	 * @return string
	 */
	public static function phone() {
		return SIW_Properties::get('phone');
	}

	/**
	 * Internationaal telefoonnummer
	 *
	 * @return string
	 */
	public static function phone_international() {
		return SIW_Properties::get('phone_international');
	}

	/**
	 * IBAN
	 *
	 * @return string
	 */
	public static function iban() {
		return SIW_Properties::get('iban');
	}

	/**
	 * RSIN
	 *
	 * @return string
	 */
	public static function rsin() {
		return SIW_Properties::get('rsin');
	}

	/**
	 * Openingstijden
	 *
	 * @return string
	 */
	public static function opening_hours() {
		return sprintf( esc_html__( 'Maandag t/m vrijdag %s - %s', 'siw' ), SIW_Properties::get('opening_time'), SIW_Properties::get('closing_time') );
	}

	/**
	 * ESC-borg
	 *
	 * @return string
	 */
	public static function esc_deposit() {
		return SIW_Formatting::format_amount( SIW_Properties::ESC_DEPOSIT );
	}

	/**
	 * Volgende ESC-deadline
	 *
	 * @return string
	 */
	public static function next_esc_deadline() {
		return siw_get_next_evs_deadline( true );
	}

	/**
	 * Volgende ESC-vertrekmaand
	 *
	 * @return string
	 */
	public static function next_esc_departure_month() {
		return siw_get_next_evs_departure_month();
	}

	/**
	 * Volgende infodag
	 *
	 * @return string
	 */
	public static function next_info_day() {
		return siw_get_next_info_day( true );
	}

	/**
	 * Inschrijfgeld Groepsproject (student)
	 *
	 * @return string
	 */
	public static function workcamp_fee_student() {
		$output = SIW_Formatting::format_amount( SIW_Properties::get('workcamp_fee_student') );
		if ( siw_is_sale_active() ) {
			$output = sprintf( '<del>%s</del>&nbsp;<ins>%s</ins>', $output, SIW_Formatting::format_amount( SIW_Properties::get('workcamp_fee_student_sale') ) );
		}
		return $output;
	}

	/**
	 * Inschrijfgeld Groepsproject (regulier)
	 *
	 * @return string
	 */
	public static function workcamp_fee_regular() {
		$output = SIW_Formatting::format_amount( SIW_Properties::get('workcamp_fee_regular') );
		if ( siw_is_sale_active() ) {
			$output = sprintf( '<del>%s</del>&nbsp;<ins>%s</ins>', $output, SIW_Formatting::format_amount( SIW_Properties::get('workcamp_fee_regular_sale') ) );
		}
		return $output;
	}

	/**
	 * Inschrijfgeld Op Maaat-project (student)
	 *
	 * @return string
	 */
	public static function tailor_made_fee_student() {
		return SIW_Formatting::format_amount( SIW_Properties::get('tailor_made_fee_student') );
	}

	/**
	 * Inschrijfgeld Op Maaat-project (regulier)
	 *
	 * @return string
	 */
	public static function tailor_made_fee_regular() {
		return SIW_Formatting::format_amount( SIW_Properties::get('tailor_made_fee_regular') );
	}

	/**
	 * Korting tweede Groepsproject
	 *
	 * @return string
	 */
	public static function discount_second_project() {
		return SIW_Formatting::format_percentage( SIW_Properties::get('discount_second_project') );
	}

	/**
	 * Korting derde Groepsproject
	 *
	 * @return string
	 */
	public static function discount_third_project() {
		return SIW_Formatting::format_percentage( SIW_Properties::get('discount_third_project') );
	}

	/**
	 * Externe link
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function external_link( $atts ) {
		extract( shortcode_atts( array(
			'url'	=> '',
			'titel'	=> '',
			), $atts, 'siw_externe_link' )
		);
		$titel = ( $titel ) ? $titel : $url;
	
		return SIW_Formatting::generate_external_link( $url, $titel );
	}

	/**
	 * Bestuursleden
	 * 
	 * @return string
	 * 
	 * @todo verplaatsen naar widget met organisatiegegevens.
	 */
	public static function board_members() {
		for ( $x = 1 ; $x <= SIW_MAX_BOARD_MEMBERS; $x++ ) {
			$board_members[] = siw_get_setting( "board_member_{$x}" );
		}
		if ( empty( $board_members ) ) {
			return;
		}
	
		$board_members_list = [];
	
		foreach ( $board_members as $board_member ) {
			if ( isset( $board_member['name'] ) ) {
				$list_item = $board_member['name'];
				if ( isset( $board_member['title'] ) ) {
					$list_item .= '<br/>' . '<i>' . $board_member['title'] . '</i>';
				}
				$board_members_list[] = $list_item;
			}
		}
		return SIW_Formatting::generate_list( $board_members_list );
	}

	/**
	 * Jaarverslagen
	 * 
	 * @return string
	 * 
	 * @todo verplaatsen naar widget met organisatiegegevens.
	 */
	public static function annual_reports() {
		$annual_reports = siw_get_annual_reports();
		if ( empty( $annual_reports ) ) {
			return;
		}
	
		$output = '';
		foreach ( $annual_reports as $year => $annual_report ) {
			if ( ! empty( $annual_report['url'] ) ) {
				$url = $annual_report['url'];
				$text = sprintf( esc_html__( 'Jaarverslag %s', 'siw' ), $year );
				$output .= SIW_Formatting::generate_link( $url, $text, [ 'class' => 'siw-download', 'target' => '_blank', 'rel' => 'noopener' ] ) . BR ;
			}
		}
		return $output;
	}

	/**
	 * Overzicht van Nederlandse projecten
	 * 
	 * @return string
	 * 
	 * @todo verplaatsen naar kaart-widget
	 */
	public static function dutch_projects() {
		$projects = siw_get_dutch_projects();
		if ( empty( $projects ) ) {
			return;
		}
		$description = '';
		foreach ( $projects as $project ) {
			$duration = SIW_Formatting::format_date_range( $project['start_date'], $project['end_date'] );
			$description .= sprintf( '<b>%s - %s</b><br/>', esc_html( $project['name'] ), esc_html( $project['province_name'] ) );
			$description .= esc_html__( 'Data:', 'siw' ) . SPACE . esc_html( $duration ) . BR;
			$description .= esc_html__( 'Deelnemers:', 'siw' ) . SPACE . esc_html( $project['participants'] ) . BR;
			$description .= esc_html__( 'Soort werk:', 'siw' ) . SPACE . $project['work_name'] . BR;
			$description .= esc_html__( 'Locatie:', 'siw' ) . SPACE . $project['city'] . ', ' . __( 'provincie', 'siw' ) . SPACE . $project['province_name'] . BR2;
		}
		return $description;
	}

	/**
	 * Lightbox met inhoud van pagina
	 *
	 * @param array $atts
	 * @return string
	 * 
	 * @todo slug als parameter en get page by path gebruiken
	 */
	public static function page_modal( $atts ) {
		extract( shortcode_atts( array(
			'link_tekst' => '',
			'pagina'     => '',
			), $atts, 'siw_pagina_lightbox' )
		);
	
		$pages = array(
			'kinderbeleid' => 'child_policy',
		);
		/* Haal pagina id op en breek af als pagina niet ingesteld is */
		$page_id = siw_get_setting( $pages[ $pagina ] . '_page' );
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
	 * Cirkeldiagram
	 *
	 * @param array $atts
	 */
	public static function pie_chart( $atts ) {
		extract( shortcode_atts( array(
			'titel'	=> '',
			'labels' => '',
			'waardes' => '' ,
			), $atts, 'siw_cirkeldiagram' )
		);
	
		/* Data-array opbouwen */
		$labels = explode( '|', $labels );
		$waardes = explode( '|', $waardes );
		$values = array_combine( $labels, $waardes );
		$data[] = "['Post', 'Percentage']";
		foreach( $values as $label => $value ) {
			$data[] = sprintf( "['%s', %s]", esc_js( $label ), esc_js( $value ) );
		}
	
		/*Optie-array opbouwen */
		$options[] = "tooltip:{text: 'percentage'}";
		$options[] = sprintf("title: '%s',", esc_js( $titel ) );
	
		/* Start inline script */
		ob_start();
		?>
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
	
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				<?php echo implode( ',', $data ); ?>
			]);
			var options = {
				<?php echo implode( ',', $options ); ?>
			};
			var chart = new google.visualization.PieChart(document.getElementById('piechart'));
			chart.draw(data, options);
		}
		jQuery(window).resize(function(){
		  drawChart();
		});
		<?php
		$inline_script = ob_get_clean();
	
		/* Script laden*/
		wp_register_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js' );
		wp_enqueue_script( 'google-charts' );
		wp_add_inline_script( 'google-charts', $inline_script );
	
		/* Grafiek */
		$pie_chart = '<div id="piechart" style="width: 100%; min-height: 450px;"></div>';
	
		return $pie_chart;
	}

	/**
	 * Leeftijd van SIW in jaren
	 * 
	 * @return string
	 */
	public static function age() {
		return SIW_Util::calculate_age( SIW_Properties::FOUNDING_DATE );
	}

}
