<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. configuratie
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'pages',
	'title'          => __( 'Pagina\'s', 'siw' ),
	'settings_pages' => 'configuration',
	'tabs'           => [
		'archive'      => __( 'Archief', 'siw' ),
		'explanation'  => __( 'Zo werkt het', 'siw' ),
		'other'        => __( 'Overig', 'siw' ),
	],
	'tab_style'      => 'left',
	'fields'         => [
		[
			'id'      => 'events_archive_page',
			'name'    => __( 'Evenementen', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'archive',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'job_postings_archive_page',
			'name'    => __( 'Vacatures', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'archive',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'workcamps_explanation_page',
			'name'    => __( 'Groepsprojecten', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'explanation',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'info_days_explanation_page',
			'name'    => __( 'Infodagen', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'explanation',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'esc_explanation_page',
			'name'    => __( 'ESC', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'explanation',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'tailor_made_explanation_page',
			'name'    => __( 'Op Maat', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'explanation',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'contact_page',
			'name'    => __( 'Contact', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'other',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'quick_search_results_page',
			'name'    => __( 'Resultaten Snel Zoeken', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'other',
			'options' => SIW_Util::get_pages(),
		],
		[
			'id'      => 'child_policy_page',
			'name'    => __( 'Kinderbeleid', 'siw' ),
			'type'    => 'select_advanced',
			'tab'     => 'other',
			'options' => SIW_Util::get_pages(),
		],
	],
];

return $data;
