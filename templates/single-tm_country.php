<?php
/*
 * (c)2017-2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Elements\World_Map;
use SIW\Elements;
use SIW\i18n;
use SIW\HTML;

get_header();
get_template_part('templates/page', 'header' );

$country = siw_get_country( siw_meta('country') );
$continent = $country->get_continent();
?>

<div id="content" class="container">
	<div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while ( have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="tm-country-<?php the_ID(); ?>">
			<div class="postclass">
				<div class="row">
					<div class="hidden-xs hidden-sm col-md-6" data-sal="slide-right" data-sal-duration="1800" data-sal-easing="ease-out-sine">
						
						<?php 
							$world_map = new World_Map();
							echo $world_map->generate( $country, 2 );
						?>
					</div>
					<div class="col-md-6" data-sal="slide-left" data-sal-duration="1800" data-sal-easing="ease-out-sine">
						<h4><?php printf( esc_html__( 'Projecten Op Maat in %s', 'siw' ), $country->get_name() );  ?></h4>
						<p><?php echo wp_kses_post( rwmb_get_value( 'introduction' ) );?></p>
						<b><?php esc_html_e( 'Dit is het type projecten dat we hier aanbieden:', 'siw' );?></b>
						<p>
							<?php
							$work_types = siw_meta( 'work_type' );
							$has_child_projects = false;
							foreach ( $work_types as $work_type ) {
								$work_type = siw_get_work_type( $work_type );
								if ( 'kinderen' == $work_type->get_slug() ) {
									$has_child_projects = true; //TODO: misschien array_key_exists gebruiken?
								}

								printf( '%s %s<br>', Elements::generate_icon( $work_type->get_icon_class(), 2, 'circle' ), $work_type->get_name() );
							}
							?>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" data-sal="fade" data-sal-duration="1850" data-sal-easing="ease-out-sine">
						<div class="siw-quote">
							<?php
							echo Elements::generate_icon( 'siw-icon-quote-left', 2 ) . SPACE;
							echo esc_html( rwmb_get_value( 'quote' ));
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6" data-sal="slide-right" data-sal-duration="1800" data-sal-easing="ease-out-sine">
					<p><?php echo wp_kses_post( rwmb_get_value( 'description' ) );?></p>
					<?php if ( $has_child_projects ) : ?>
						<p>
						<?php
							esc_html_e( 'Goed om te weten: SIW beoordeelt projecten met kinderen volgens de richtlijnen van het Better Care Network.', 'siw' );
							echo do_shortcode(' [siw_pagina_lightbox link_tekst="Lees meer over ons beleid." pagina="kinderbeleid"]');
							?>
						</p>
					<?php endif ?>
					<p>
					<?php 
						echo sprintf( esc_html__( 'Samen met de regiospecialist %s ga je aan de slag om van jouw idee werkelijkheid te maken.', 'siw' ), $continent->get_name() ) . SPACE; 
						echo esc_html__( 'Word jij hiervan enthousiast, ga dan naar onze pagina over Op Maat projecten.', 'siw' ) . BR2;
						$tailor_made_page_link = i18n::get_translated_page_url( siw_get_option( 'tailor_made_explanation_page' ) );
						echo HTML::generate_link( $tailor_made_page_link, __( 'Meld je aan', 'siw' ), [ 'class' => 'kad-btn kad-btn-primary' ] );	
					?>
					</div>
					<div class="col-md-6" data-sal="slide-left" data-sal-duration="1800" data-sal-easing="ease-out-sine">
						<?php
						$images = siw_meta( 'image', ['limit' => 1 ] );
						$image = reset( $images );
						echo wp_get_attachment_image( $image['ID'], 'large'); ?>
					</div>
				</div>
				<div class="row" style="padding-top:40px;">
					<div class="col-md-12" style="padding-bottom:20px;">
						<h3><?php esc_html_e( 'Zo werkt het', 'siw' );?></h3>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo Elements::generate_icon('siw-icon-file-signature', 4, 'circle' );?><br>
						<h5><?php esc_html_e( '1. Aanmelding', 'siw' ); ?></h5>
						<p><?php esc_html_e( 'Ben je geïnteresseerd in een Project Op Maat? Meld je dan direct aan via de website.', 'siw' );?></p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo Elements::generate_icon('siw-icon-handshake', 4, 'circle' );?><br>
						<h5><?php esc_html_e( '2. Kennismaking', 'siw' ); ?></h5>
						<p><?php esc_html_e( 'Na het kennismakingsgesprek stelt de regiospecialist een selectie van drie Projecten Op Maat voor je samen.', 'siw' );?></p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo Elements::generate_icon('siw-icon-clipboard-check', 4, 'circle' );?><br>
						<h5><?php esc_html_e( '3. Bevestiging', 'siw' ); ?></h5>
						<p><?php esc_html_e( 'Als je een passend Project Op Maat hebt gekozen, volgt de betaling. Vervolgens gaat de regiospecialist voor je aan de slag.', 'siw' );?></p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo Elements::generate_icon('siw-icon-tasks', 4, 'circle' );?><br>
						<h5><?php esc_html_e( '4. Voorbereiding', 'siw' ); ?></h5>
						<p><?php esc_html_e( 'Kom naar de Infodag zodat je goed voorbereid aan jouw avontuur kan beginnen.', 'siw' );?></p>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action( 'siw_tm_country_footer' );?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>

<?php get_footer(); ?>
