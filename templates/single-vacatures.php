<?php
/*
 * (c)2017-2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part('templates/post', 'header' );

global $post;
$job_data = siw_get_job_data( $post->ID ); 
$panes = [
		[
		'title'   => __( 'Wat ga je doen?', 'siw' ),
		'content' => $job_data['wat_ga_je_doen'] . SIW_Formatting::generate_list( $job_data['wat_ga_je_doen_lijst'] ),
	],
	[
		'title'   => __( 'Wie ben jij?', 'siw' ),
		'content' =>  $job_data['wie_ben_jij'] . SIW_Formatting::generate_list( $job_data['wie_ben_jij_lijst'] ),
	],
	[
		'title'   => __( 'Wat bieden wij jou?', 'siw' ),
		'content' => $job_data['wat_bieden_wij_jou'] . SIW_Formatting::generate_list( $job_data['wat_bieden_wij_jou_lijst'] ),
	],
	[
		'title'   => __( 'Wie zijn wij?', 'siw' ),
		'content' => siw_get_option( 'job_postings_organization_profile' ),
	],
];
$content = SIW_Formatting::generate_accordion( $panes );
?>

<div id="content" class="container">
	<div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while ( have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="vacatures-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1>
						<?php the_title();?>
					</h1>
					<h5>
						<?php
						( isset( $job_data['betaald'] ) && true == $job_data['betaald'] ) ? esc_html_e( 'Betaalde functie', 'siw') : esc_html_e( 'Vrijwillige functie', 'siw');
						echo( ! empty( $job_data['uur_per_week'] ) ? SPACE . '(' . sprintf( esc_html__( '%s uur/week', 'siw') . ')', $job_data['uur_per_week'] ) : ''); ?>
					</h5>
					<hr>
				</header>
				<div class="row">
					<div class="col-md-7">
						<?= ( ! empty( $job_data['inleiding'] ) ? wp_kses_post( wpautop( $job_data['inleiding'] ) ) : ''  ); ?>
					</div>
					<div class="col-md-5">
						<?php if ( ! empty( $job_data['highlight_quote'] ) ): ?>
						<div class="pullquote-center vacature-quote">
							<?= esc_html( $job_data['highlight_quote'] );?>
						</div>
						<?php endif ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7">
						<h3>
							<?= esc_html__( 'Wat houdt deze vacature in?', 'siw' );?>
						</h3>
						<?= do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
					<?php if ( $job_data['deadline_datum'] >= date('Y-m-d') ):?>
						<h3>
							<?= esc_html__('Meer weten?', 'siw');?>
						</h3>
						<p>
							<?= sprintf( wp_kses_post( __( 'Voor meer informatie kun je contact opnemen met:', 'siw' ) . '<br />%s, <a class="email" href="mailto:%s">%s</a>'), $job_data['contactpersoon_naam'],  $job_data['contactpersoon_email'], $job_data['contactpersoon_email'] );?>
						</p>
						<h3>
							<?= esc_html__( 'Solliciteren?','siw' );?>
						</h3>
						<p>
							<?= sprintf( wp_kses_post( __( 'Je motivatie met cv kun je uiterlijk %s sturen naar:<br />%s, <a class="email" href="mailto:%s">%s</a> onder vermelding van "Sollicitatie %s"', 'siw' ) ), $job_data['deadline'], $job_data['solliciteren_naam'], $job_data['solliciteren_email'], $job_data['solliciteren_email'], the_title_attribute( array( 'echo' => false ) ) );?>
						</p>
						<p>
							<?= wp_kses_post( $job_data['toelichting_solliciteren'] );?>
						</p>
						<?php else: ?>
						<h5>
							<?= esc_html__( 'Het is helaas niet meer mogelijk om op deze vacature te reageren', 'siw' );?>
						</h5>
						<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action( 'siw_vacature_footer' );?>
				</footer>
			</div>
			<?= $job_data['json_ld']?>
		</article>
<?php endwhile; ?>

<?php get_footer(); ?>