<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Templates voor teksten van een Groepsproject
 * 
 * @author      Maarten Bruna
 * @package     SIW\WooCommerce
 * @copyright   2018, SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_workcamp_description_templates', function( $templates ) {

	$templates[] = [
		__( 'Zet je in voor een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Als vrijwilliger zal je werkzaamheden gaan uitvoeren gericht op {{ work_type }}.', 'siw' ),
		__( 'Lees snel verder voor meer informatie over de kosten, de werkzaamheden, de accommodatie en projectlocatie.', 'siw' ),
		__( 'Heb je een vraag over dit project?', 'siw' ),
		__( 'Laat je gegevens achter bij "Stel een vraag en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	];
	$templates[] = [
		__( 'Steek je handen uit de mouwen tijdens een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Jouw werkzaamheden richten zich op {{ work_type }}.', 'siw' ),
		__( 'Lees snel verder voor meer informatie over het tarief, werk, accommodatie en projectlocatie.', 'siw' ),
		__( 'Heb je een vraag over dit project?', 'siw' ),
		__( 'Laat je gegevens achter bij "Stel een vraag" en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	];
	$templates[] = [
		__( 'Ga aan de slag als vrijwilliger tijdens een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers in de leeftijd van {{ ages }}.', 'siw' ),
		__( 'Je gaat werkzaamheden uitvoeren gericht op {{ work_type }}.', 'siw' ),
		__( 'Lees snel verder voor meer informatie over het tarief, werk, accommodatie en projectlocatie.', 'siw' ),
		__( 'Heb je een vraag over dit project?', 'siw' ),
		__( 'Laat je gegevens achter bij "Stel een vraag" en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	];
	return $templates;
});

add_filter( 'siw_workcamp_seo_description_templates', function( $templates ) {

	$templates[] = [
		__( 'Op zoek naar een vrijwilligersproject in {{ country }}?', 'siw' ),
		__( 'Zet je in voor een {{ project_type }} gericht op {{ work_type }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers.', 'siw' ),
	];
	$templates[] = [
		__( 'Op zoek naar een vrijwilligersproject gericht op {{ work_type }}?', 'siw' ),
		__( 'Zet je in voor een {{ project_type }} in {{ country }}.', 'siw' ),
		__( 'Dit project vindt plaats van {{ dates }} en biedt ruimte aan {{ participants }} deelnemers.', 'siw' ),
	];
	return $templates;
});
