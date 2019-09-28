<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van shortcodes
 * 
 * @author    Maarten Bruna
 * @package   SIW\Data
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'shortcode' => 'kvk',
		'title'     => __( 'KVK-nummer', 'siw' ),
	],
	[
		'shortcode' => 'email',
		'title'     => __( 'E-mailadres', 'siw' ),
	],
	[
		'shortcode' => 'email_link',
		'title'     => __( 'E-mailadres (link)', 'siw' ),
	],
	[
		'shortcode' => 'telefoon',
		'title'     => __( 'Telefoonnummer', 'siw' ),
	],
	[
		'shortcode' => 'telefoon_internationaal',
		'title'     => __( 'Telefoonnummer (internationaal)', 'siw' ),
	],
	[
		'shortcode' => 'iban',
		'title'     => __( 'IBAN', 'siw' )
	],
	[
		'shortcode' => 'rsin',
		'title' => __( 'RSIN', 'siw' )
	],
	[
		'shortcode' => 'openingstijden',
		'title' => __( 'Openingstijden', 'siw' )
	],
	[
		'shortcode' => 'esc_borg',
		'title' => __( 'ESC-borg', 'siw' )
	],
	[
		'shortcode' => 'esc_volgende_deadline',
		'title' => __( 'Volgende ESC-deadline', 'siw' )
	],
	[
		'shortcode' => 'esc_volgende_vertrekmoment',
		'title' => __( 'Volgende ESC-vertrekmoment', 'siw' )
	],
	[
		'shortcode' => 'volgende_infodag',
		'title' => __( 'Volgende infodag', 'siw' )
	],
	[
		'shortcode' => 'groepsproject_tarief_student',
		'title' => __( 'Groepsprojecten - Studententarief', 'siw' )
	],
	[
		'shortcode' => 'groepsproject_tarief_regulier',
		'title' => __( 'Groepsprojecten - Regulier tarief', 'siw' )
	],
	[
		'shortcode' => 'op_maat_tarief_student',
		'title' => __( 'Op Maat - Studententarief', 'siw' )
	],
	[
		'shortcode' => 'op_maat_tarief_regulier',
		'title' => __( 'Op Maat - Regulier tarief', 'siw' )
	],
	[
		'shortcode' => 'korting_tweede_project',
		'title' => __( 'Korting tweede project', 'siw' )
	],
	[
		'shortcode' => 'korting_derde_project',
		'title' => __( 'Korting derde project', 'siw' )
	],
	[
		'shortcode'  => 'externe_link',
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
	[
		'shortcode'  => 'pagina_lightbox',
		'title'      => __( 'Pagina-lightbox', 'siw' ),
		'attributes' => [
			[
				'attr'  => 'link_tekst',
				'type'  => 'text',
				'title' => __( 'Linktekst', 'siw' ),
			],
			[
				'attr'  => 'pagina',
				'type'  => 'select',
				'title' => __( 'Pagina', 'siw' ),
				'options' => [
					'kinderbeleid' => __( 'Beleid kinderprojecten', 'siw' ),
				],
			],
		],
	],
	[
		'shortcode'  => 'leeftijd',
		'title'      => __( 'Leeftijd van SIW', 'siw' )
	],
];

return $data;
