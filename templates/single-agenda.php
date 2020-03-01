<?php
/*
 * (c)2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Elements\Google_Maps;
use SIW\HTML;
use SIW\Formatting;

get_header();
get_template_part('templates/post', 'header' );

global $post;
	$event_data = siw_get_event_data( $post->ID );

	$location_map = new Google_Maps();
	$location_map->add_location_marker(
		sprintf( '%s, %s %s', $event_data['address'], $event_data['postal_code'], $event_data['city'] ),
		$event_data['location'],
		sprintf( '%s, %s %s', $event_data['address'], $event_data['postal_code'], $event_data['city'] )
	);
	$location_map->set_options(['zoom' => 15 ] );

	$hide_application_form_days_before_info_day	= 1; //TODO:property van maken 
	$limit_date = date( 'Y-m-d', time() + ( $hide_application_form_days_before_info_day * DAY_IN_SECONDS ) );
	$agenda_page_url = get_permalink( siw_get_option( 'events_archive_page' ) );
?>

<div id="content" class="container">
	<div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while ( have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="agenda-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1><?php the_title();?></h1>
					<h5>
						<?= esc_html( $event_data['duration'] );?>
					</h5>
					<hr>
				</header>
				<div class="row">
					<div class="col-md-7">
						<?= wp_kses_post( wpautop( $event_data['description'] ) ); ?>
					</div>
					<div class="col-md-5">
						<?php if ( ! empty( $event_data['highlight_quote'] ) ): ?>
						<div class="pullquote-center agenda-quote">
							<?= esc_html( $event_data['highlight_quote'] );?>
						</div>
						<?php endif ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<h3>
							<?= esc_html__( 'Locatie', 'siw' );?>
						</h3>
						<p>
							<b>
							<?= esc_html( $event_data['location'] ); ?><br/>
							<?= esc_html( $event_data['address'] ); ?><br/>
							<?= esc_html( $event_data['postal_code'] . ' ' . $event_data['city'] ); ?><br/>
							</b>
						</p>
						<?php $location_map->render();?>
					</div>
					<div class="col-md-6">
						<h3>
							<?= esc_html__( 'Aanmelden', 'siw' );?>
						</h3>
						<?php if ( $event_data['end_date'] > date( 'Y-m-d') ):?>
							<?php
								if ( 'formulier' == $event_data['application'] ) {
									if ( $event_data['start_date'] >= $limit_date ) {
										$default_date = sanitize_title( Formatting::format_date( $event_data['start_date'], false ) );
										echo do_shortcode( sprintf( '[caldera_form id="infodag" datum="%s"]', $default_date) );
									}
									else {
										esc_html_e( 'Het is helaas niet meer mogelijk om je aan te melden.','siw' );
									}
								} else {
									echo wp_kses_post( wpautop( $event_data['application_explanation'] ) );
									if ( $event_data['application_link_url'] ) {
										echo HTML::generate_external_link( $event_data['application_link_url'], $event_data['application_link_text'] );
									}
								}
								else:
								?>
								<p>
									<?php printf( wp_kses_post( __( 'Dit evenement is helaas al afgelopen. Bekijk de toekomstige evenementen in de <a href="%s">agenda</a>', 'siw' ) ), esc_url( $agenda_page_url ) );?>
								</p>
							<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action('siw_agenda_footer');?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>

<?php get_footer(); ?>