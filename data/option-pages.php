<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Optiepagina's
 *
 * @author    Maarten Bruna
 * @package   SIW\Data
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'id'          => 'dates',
		'menu_title'  => __( 'Datums', 'siw' ),
		'capability'  => 'edit_posts',
	],
	[
		'id'          => 'configuration',
		'menu_title'  => __( 'Configuratie', 'siw' ),
		'capability'  => 'manage_options',
	],
	[
		'id'          => 'workcamps',
		'menu_title'  => __( 'Groepsprojecten', 'siw' ),
		'capability'  => 'manage_options',
	],
	[
		'id'         => 'dutch-projects',
		'menu_title' => __( 'Nederlandse Projecten', 'siw' ),
		'capability' => 'edit_posts',
	],
	[
		'id'         => 'countries',
		'capability' => 'manage_options',
		'columns'    => 1,
		'menu_title' => __( 'Landen', 'siw' ),
	],
	[
		'id'         => 'organization',
		'capability' => 'edit_posts',
		'menu_title' => __( 'Organisatie', 'siw' ),
	],
	[
		'id'            => 'tailor-made',
		'menu_title'    => __( 'Op Maat', 'siw' ),
		'capability'    => 'manage_options',
	],
	[
		'id'          => 'job-postings',
		'menu_title'  => __( 'Vacatures', 'siw' ),
		'capability'  => 'manage_options',
	],
	[
		'id'         => 'emails',
		'capability' => 'manage_options',
		'menu_title' => __( 'E-mails', 'siw' ),
	],

];
return $data;