<?php

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
		__( 'Als vrijwilliger zal je werkzaamheden gaan uitvoeren gericht op {{ work_type }}.', 'siw' ) . BR2,
		__( 'Lees snel verder voor meer informatie over de kosten, de werkzaamheden, de accommodatie en projectlocatie.', 'siw' ),
		__( 'Heb je een vraag over dit project?', 'siw' ),
		__( 'Laat je gegevens achter bij "Stel een vraag" en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	],
	[
		__( 'Steek je handen uit de mouwen tijdens een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Jouw werkzaamheden richten zich op {{ work_type }}.', 'siw' ) . BR2,
		__( 'Lees snel verder voor meer informatie over het tarief, werk, accommodatie en projectlocatie.', 'siw' ),
		__( 'Heb je een vraag over dit project?', 'siw' ),
		__( 'Laat je gegevens achter bij "Stel een vraag" en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	],
	[
		__( 'Ga aan de slag als vrijwilliger tijdens een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Je gaat werkzaamheden uitvoeren gericht op {{ work_type }}.', 'siw' ) . BR2,
		__( 'Lees snel verder voor meer informatie over het tarief, werk, accommodatie en projectlocatie.', 'siw' ),
		__( 'Heb je een vraag over dit project?', 'siw' ),
		__( 'Laat je gegevens achter bij "Stel een vraag" en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	],
];
return $data;