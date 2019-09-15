/** global: ApexCharts */

/**
 * @file      Functies t.b.v. grafieken
 * @author    Maarten Bruna 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

siwChartsInitAll();

/**
 * Initialiseert alle grafieken
 */
function siwChartsInitAll() {
	//Zoek alle grafieken
	var charts = document.querySelectorAll( '.siw-chart' );

	//Intialiseer elke grafiek
	charts.forEach( function ( el ) {
		siwChartsInitSingle( el );
	})
}

/**
 * Initialiseert een grafiek
 *
 * @param {Element} el
 */
function siwChartsInitSingle( el ) {
	var options = JSON.parse( el.dataset.options );

	var chart = new ApexCharts(
		el,
		options
	);
	chart.render();
}
