<?php
/*
 * (c)2017-2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part('templates/page', 'header' );

$country = siw_get_country( rwmb_meta('country') );
$continent = $country->get_continent();
?>

<div id="content" class="container">
	<div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while ( have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="tm-country-<?php the_ID(); ?>">
			<div class="postclass">
				<div class="row">
					<div class="hidden-xs hidden-sm col-md-6 kt-pb-animation kt-pb-fadeInLeft kt-pb-duration-1800 kt-pb-delay-0">
						<?php 
							$world_map = new SIW_Element_World_Map();
							echo $world_map->generate( $country, 2 );
						?>
						<style>
							/* TODO: verplaatsen naar stylesheet  */
							svg {width: 100%; height: auto;}
						</style>
					</div>
					<div class="col-md-6 kt-pb-animation kt-pb-fadeInRight kt-pb-duration-1800 kt-pb-delay-0">
						<h4><?php printf( esc_html__( 'Projecten Op Maat in %s', 'siw' ), $country->get_name() );  ?></h4>
						<p><?php echo wp_kses_post( rwmb_get_value( 'introduction' ) );?></p>
						<b><?php esc_html_e( 'Dit is het type projecten dat we hier aanbieden:', 'siw' );?></b>
						<p>
							<?php
							$work_types = rwmb_meta( 'work_type' );
							$child_projects = false;
							foreach ( $work_types as $work_type ) {
								$work_type = siw_get_work_type( $work_type );
								if ( 'kinderen' == $work_type->get_slug() ) {
									$child_projects = true; //TODO: misschien array_key_exists gebruiken?
								}

								printf( '%s %s<br>', SIW_Formatting::generate_icon( $work_type->get_icon_class(), 1, 'circle' ), $work_type->get_name() );
							}
							?>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 kt-pb-animation kt-pb-fadeIn kt-pb-duration-1800 kt-pb-delay-0">
						<div class="siw-quote">
							<?php
							echo SIW_Formatting::generate_icon( 'siw-icon-quote-left', 1 ) . SPACE;
							echo esc_html( rwmb_get_value( 'quote' ));
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 kt-pb-animation kt-pb-fadeInLeft kt-pb-duration-1800 kt-pb-delay-0">
					<p><?php echo wp_kses_post( rwmb_get_value( 'description' ) );?></p>
					<?php if ( true == $child_projects ) : ?>
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
						$tailor_made_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'tailor_made_explanation_page' ) );
						echo SIW_Formatting::generate_link( $tailor_made_page_link, __( 'Meld je aan', 'siw' ), [ 'class' => 'kad-btn kad-btn-primary' ] );	
					?>
					</div>
					<div class="col-md-6 kt-pb-animation kt-pb-fadeInRight kt-pb-duration-1800 kt-pb-delay-0">
						<?php
						$images = rwmb_meta( 'image', ['limit' => 1 ] );
						$image = reset( $images );
						echo wp_get_attachment_image( $image['ID'], 'large'); ?>
					</div>
				</div>
				<div class="row" style="padding-top:40px;">
					<div class="col-md-12" style="padding-bottom:20px;">
						<h3><?php esc_html_e( 'Zo werkt het', 'siw' );?></h3>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo SIW_Formatting::generate_icon('siw-icon-file-signature', 2, 'circle' );?><br>
						<h5><?php esc_html_e( '1. Aanmelding', 'siw' ); ?></h5>
						<p><?php esc_html_e( 'Ben je geïnteresseerd in een Project Op Maat? Meld je dan direct aan via de website.', 'siw' );?></p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo SIW_Formatting::generate_icon('siw-icon-handshake', 2, 'circle' );?><br>
						<h5><?php esc_html_e( '2. Kennismaking', 'siw' ); ?></h5>
						<p><?php esc_html_e( 'Na het kennismakingsgesprek stelt de regiospecialist een selectie van drie Projecten Op Maat voor je samen.', 'siw' );?></p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo SIW_Formatting::generate_icon('siw-icon-clipboard-check', 2, 'circle' );?><br>
						<h5><?php esc_html_e( '3. Bevestiging', 'siw' ); ?></h5>
						<p><?php esc_html_e( 'Als je een passend Project Op Maat hebt gekozen, volgt de betaling. Vervolgens gaat de regiospecialist voor je aan de slag.', 'siw' );?></p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo SIW_Formatting::generate_icon('siw-icon-tasks', 2, 'circle' );?><br>
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