<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Voeg widget met EVS-aanmeldingen toe */
add_action( 'wp_dashboard_setup', function() {
	wp_add_dashboard_widget(
		'siw_evs_applications_widget',
		__( 'EVS aanmeldingen', 'siw' ),
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_vfb_applications_per_month( 'evs', 5 ),
		)
	);
} );


/* Voeg widget met Op Maat-aanmeldingen toe */
add_action( 'wp_dashboard_setup', function() {
	wp_add_dashboard_widget(
		'siw_op_maat_applications_widget',
		__( 'Op maat aanmeldingen', 'siw' ),
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_vfb_applications_per_month( 'op_maat', 5 ),
		)
	);
} );


/* Voeg widget met Infodag-aanmeldingen toe */
add_action( 'wp_dashboard_setup', function() {
	wp_add_dashboard_widget(
		'siw_community_day_applications_widget',
		__( 'Infodag aanmeldingen', 'siw' ),
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_vfb_applications_per_month( 'community_day', 5 ),
		)
	);
} );


/* Voeg widget met GP-aanmeldingen toe */
add_action( 'wp_dashboard_setup', function() {
	wp_add_dashboard_widget(
		'siw_woocommerce_applications_widget',
		__( 'Groepsprojecten aanmeldingen', 'siw' ),
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_woocommerce_applications_per_month( 5 ),
		)
	);
} );


/**
 * Functie om widget met aantal aanmeldingen te tonen
 * @param array $var
 * @param array $args
 *
 * @return void
 */
function siw_display_applications_widget( $var, $args ) {
	$applications = $args['args']['applications'];
	if ( ! empty( $applications ) ) {

		foreach ( $applications as $application ) {
			$application_months[] = sprintf( '%s (%d)', siw_get_month_in_text( $application['application_month'] ), $application['application_count'] );
			$application_counts[] = (int) $application['application_count'];
		}
		$application_months = array_reverse( $application_months );
		$application_counts = array_reverse( $application_counts );

		$highest_value = max( $application_counts );
		$data_points = count( $application_counts );
		$bar_width = 100 / $data_points - 2;
		$total_height = 120;
		?>
		<div class="comment-stat-bars" style="height:<?php echo $total_height ?>px;">
			<?php
				foreach ( $application_counts as $count ) :
					$count_percentage = $count / $highest_value;
					$bar_height = $total_height * $count_percentage;
					$border_width = $total_height - $bar_height;
			?>
			<div class="comment-stat-bar" style="height:<?php echo $total_height ?>px; border-top-width:<?php echo $border_width ?>px; width: <?php echo $bar_width ?>%;"></div>
			<?php endforeach ?>
		</div>
		<div class='comment-stat-labels'>
			<?php foreach ( $application_months as $month ) : ?>
			<div class='comment-stat-label' style='width: <?php echo $bar_width ?>%;'><?php echo ucfirst( $month ) ?></div>
		<?php endforeach ?>
		</div>
		<div class='comment-stat-caption'><?php printf( esc_html__( 'Aanmeldingen van de afgelopen %d maanden', 'siw' ), $data_points );?></div>

		<?php
	}
	else{
	?>
	<div class='comment-stat-caption'><?php esc_html_e( 'Geen aanmeldingen gevonden', 'siw' )?></div>
	<?php
	}
}


/**
 * Geeft een array terug met aantal aanmeldingen per maand voor een VFB-formulier
 * @param  string $form
 * @param  int $results
 * @return array
 */
function siw_get_vfb_applications_per_month( $form, $results ) {
	$form_id = siw_get_vfb_form_id( $form );
	global $wpdb;
	$query =	"SELECT Year($wpdb->posts.post_date)  AS application_year,
						Month($wpdb->posts.post_date) AS application_month,
						Count(*) 				      AS application_count
					FROM   $wpdb->posts
						JOIN $wpdb->postmeta
							ON $wpdb->posts.id = $wpdb->postmeta.post_id
					WHERE  $wpdb->posts.post_type = 'vfb_entry'
						AND $wpdb->postmeta.meta_key = '_vfb_form_id'
						AND $wpdb->postmeta.meta_value = %d
					GROUP  BY application_year,
							application_month
					ORDER  BY application_year DESC,
							application_month DESC
					LIMIT  %d; ";

	$applications = $wpdb->get_results( $wpdb->prepare( $query, $form_id, $results ), ARRAY_A );

	return $applications;
}


/**
 * Geeft een array terug met aantal GP-aanmelding per maand
 * @param int $results aantal maanden
 *
 * @return array
 */
function siw_get_woocommerce_applications_per_month( $results ) {
	global $wpdb;
	$query =	"SELECT Year($wpdb->posts.post_date)  AS application_year,
						Month($wpdb->posts.post_date) AS application_month,
						Count(*) 				      AS application_count
					FROM   $wpdb->posts
					WHERE  $wpdb->posts.post_type = 'shop_order'
						AND $wpdb->posts.post_status IN ( 'wc-processing', 'wc-completed' )
					GROUP  BY application_year,
							application_month
					ORDER  BY application_year DESC,
							application_month DESC
					LIMIT  %d; ";
	$applications_per_month = $wpdb->get_results( $wpdb->prepare( $query, $results ), ARRAY_A );
	return $applications_per_month;
}
