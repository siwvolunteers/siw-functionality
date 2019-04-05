<?php
/*
 * (c)2017-2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part('templates/post', 'header' );


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
					<div class="col-md-6">
						<?php echo SIW_Formatting::generate_world_map( $country );?>
					<style>
						svg {width: 100%; height: auto;}
					</style>
					</div>
					<div class="col-md-6">
						<h4><?php printf( esc_html__( 'Op Maat in %s', 'siw' ), $country->get_name() );  ?></h4>
						<p><?php echo wp_kses_post( rwmb_get_value( 'introduction' ) );?></p>
						<b><?php esc_html_e( 'Dit is het type projecten dat we hier aanbieden:', 'siw' );?></b>
						<p>
							<?php
							$work_types = rwmb_meta( 'work_type' );
							foreach ( $work_types as $work_type ) {
								$work_type = siw_get_work_type( $work_type );
								printf( '%s %s<br>', SIW_Formatting::generate_icon( $work_type->get_icon_class(), 1, 'circle' ), $work_type->get_name() );
							}
							?>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="siw-quote">
							<?php
							echo SIW_Formatting::generate_icon( 'siw-icon-quote-left', 1 ) . SPACE;
							echo esc_html( rwmb_get_value( 'quote' ));
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
					<p><?php echo wp_kses_post( rwmb_get_value( 'description' ) );?></p>
					<p>
					<?php 
						echo sprintf( esc_html__( 'Samen met de regiospecialist %s ga je aan de slag om van jouw idee werkelijkheid te maken.', 'siw' ), $continent->get_name() ) . SPACE; 
						echo esc_html__( 'Word jij hiervan enthousiast, ga dan naar onze pagina over Op Maat projecten.', 'siw' ) . BR2;
						$tailor_made_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'tailor_made_explanation_page' ) );
						echo SIW_Formatting::generate_link( $tailor_made_page_link, __( 'Meld je aan', 'siw' ), [ 'class' => 'kad-btn kad-btn-primary' ] );	
					?>
					</div>
					<div class="col-md-6">
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
						<p>Ben je geïnteresseerd in een Project Op Maat? Meld je dan [link naar aanmeldformulier] direct aan via de website.</p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo SIW_Formatting::generate_icon('siw-icon-handshake', 2, 'circle' );?><br>
						<h5>2. Kennismaking</h5>
						<p>Na het kennismakingsgesprek stelt de regiospecialist een selectie van drie Projecten Op Maat voor je samen.</p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo SIW_Formatting::generate_icon('siw-icon-clipboard-check', 2, 'circle' );?><br>
						<h5>3. Bevestiging</h5>
						<p>Als je een passend Project Op Maat hebt gekozen, volgt de betaling. Vervolgens gaat de regiospecialist voor je aan de slag.</p>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<?php echo SIW_Formatting::generate_icon('siw-icon-tasks', 2, 'circle' );?><br>
						<h5>4. Voorbereiding</h5>
						<p>Kom naar de Infodag [link naar Infodag] zodat je goed voorbereid aan jouw avontuur kan beginnen.</p>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action( 'siw_agenda_footer' );?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>

<?php get_footer(); ?>