<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SEO-projectbeschrijvingen voor import
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		__( 'Op zoek naar een vrijwilligersproject in {{ country }}?', 'siw' ),
		__( 'Zet je in voor een {{ project_type }} gericht op {{ work_type }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers.', 'siw' ),
	],
	[
		__( 'Op zoek naar een vrijwilligersproject gericht op {{ work_type }}?', 'siw' ),
		__( 'Zet je in voor een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers.', 'siw' ),
	],
];
return $data;