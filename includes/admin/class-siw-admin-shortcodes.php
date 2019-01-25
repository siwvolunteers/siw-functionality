<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcodes in admin
 * 
 * @package SIW\Admin
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo vervangen kadence menu door eigen menu
 */
class SIW_Admin_Shortcodes {

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {
		$self = new self();

		$shortcodes = [
			'siw_kvk'=> [
				'title' => __( 'KVK-nummer', 'siw' )
			],
			'siw_email' => [
				'title' => __( 'E-mailadres', 'siw' )
			],
			'siw_email_link' => [
				'title' => __( 'E-mailadres (link)', 'siw' )
			],
			'siw_telefoon' => [
				'title' => __( 'Telefoonnummer', 'siw' )
			],
			'siw_telefoon_internationaal' => [
				'title' => __( 'Telefoonnummer (internationaal)', 'siw' )
			],
			'siw_iban' => [
				'title' => __( 'IBAN', 'siw' )
			],
			'siw_rsin' => [
				'title' => __( 'RSIN', 'siw' )
			],
			'siw_openingstijden' => [
				'title' => __( 'Openingstijden', 'siw' )
			],
			'siw_evs_borg' => [
				'title' => __( 'EVS-borg', 'siw' )
			],
			'siw_evs_volgende_deadline' => [
				'title' => __( 'Volgende EVS-deadline', 'siw' )
			],
			'siw_evs_volgende_vertrekmoment' => [
				'title' => __( 'Volgende EVS-vertrekmoment', 'siw' )
			],
			'siw_volgende_infodag' => [
				'title' => __( 'Volgende infodag', 'siw' )
			],
			'siw_groepsproject_tarief_student' => [
				'title' => __( 'Groepsprojecten - Studententarief', 'siw' )
			],
			'siw_groepsproject_tarief_regulier' => [
				'title' => __( 'Groepsprojecten - Regulier tarief', 'siw' )
			],
			'siw_op_maat_tarief_student' => [
				'title' => __( 'Op Maat - Studententarief', 'siw' )
			],
			'siw_op_maat_tarief_regulier' => [
				'title' => __( 'Op Maat - Regulier tarief', 'siw' )
			],
			'siw_korting_tweede_project' => [
				'title' => __( 'Korting tweede project', 'siw' )
			],
			'siw_korting_derde_project' => [
				'title' => __( 'Korting derde project', 'siw' )
			],
			'siw_bestuursleden' => [
				'title' => __( 'Bestuursleden', 'siw' )
			],
			'siw_jaarverslagen' => [
				'title' => __( 'Jaarverslagen', 'siw' )
			],
			'siw_nederlandse_projecten' => [
				'title' => __( 'Nederlandse Projecten', 'siw' )
			],
			'siw_externe_link' => [
				'title' => __( 'Externe link', 'siw' ),
				'attr'  => [
					'url' => [
						'type'  =>'text',
						'title' => __( 'Url', 'siw' ),
					],
					'titel' => [
						'type'  => 'text',
						'title' => __( 'Titel', 'siw' ),
					],
				],
			],
			'siw_pagina_lightbox' => [
				'title' => __( 'Pagina-lightbox', 'siw' ),
				'attr'  => [
					'link_tekst' => [
						'type'  => 'text',
						'title' => __( 'Link tekst', 'siw' ),
					],
					'pagina' => [
						'type'    => 'select',
						'title'   => __( 'Pagina', 'siw' ),
						'default' => '',
						'values'  => [
							'kinderbeleid' => __( 'Beleid kinderprojecten', 'siw' ),
						],
					],
				],
			],
			'siw_leeftijd' => [
				'title' => __( 'Leeftijd van SIW', 'siw' )
			],
		];

		foreach ( $shortcodes as $shortcode => $parameters ) {
			$self->add( $shortcode, $parameters );
		}
	}

	/**
	 * Voegt shortcode toe aan Pinnacle menu
	 *
	 * @param string $shortcode
	 * @param array $parameters
	 */
	public function add( $shortcode, $parameters ) {
		add_filter( 'kadence_shortcodes', function( $pinnacle_shortcodes ) use( $shortcode, $parameters ) {
			$parameters['title'] = '[SIW] - ' . $parameters['title'];
			$pinnacle_shortcodes[ $shortcode ] = $parameters;
			return $pinnacle_shortcodes;
		});
	}
}