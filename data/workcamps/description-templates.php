<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Omschrijvingen van groepsprojecten voor import
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		__( 'Zet je in voor een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Als vrijwilliger zal je werkzaamheden gaan uitvoeren gericht op {{ work_type }}.', 'siw' ),
	],
	[
		__( 'Steek je handen uit de mouwen tijdens een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Jouw werkzaamheden richten zich op {{ work_type }}.', 'siw' ),
	],
	[
		__( 'Ga aan de slag als vrijwilliger tijdens een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Je gaat werkzaamheden uitvoeren gericht op {{ work_type }}.', 'siw' ),
	],
];
return $data;