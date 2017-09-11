<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */

//TODO: Widget van maken

add_shortcode( 'siw_cirkeldiagram', function( $atts ) {
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
	wp_enqueue_script( 'google-charts' );
	wp_add_inline_script( 'google-charts', $inline_script );

	/* Grafiek */
	$pie_chart = '<div id="piechart" style="width: 100%; min-height: 450px;"></div>';

	return $pie_chart;
});
//
