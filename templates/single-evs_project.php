<?php
get_header();
get_template_part('templates/post', 'header');

global $post;
$evs_project_data = siw_get_evs_project_data( $post->ID );
$panes = [
	[
		'title' => __( 'Wat ga je doen?', 'siw' ),
		'content' => $evs_project_data['wat_ga_je_doen'],
	],
	[
		'title' => __( 'Bij welke organisatie ga je werken?', 'siw' ),
		'content' => $evs_project_data['organisatie'],
	],
	[
		'title' => __( 'Interesse?', 'siw' ),
		'content' => '[caldera_form id="contact_evs"]',
	],
];
$content = SIW_Formatting::generate_accordion( $panes );
?>

<div id="content" class="container">
	<div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class() ?> id="evs-project-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1><?php echo esc_html( $evs_project_data['title'] ); ?></h1>
					<h5><?php echo ucfirst( esc_html( $evs_project_data['projectduur'] ) ); ?></h5>
					<?php if ( ! empty( $evs_project_data['highlight_quote'] ) ): ?>
					<div class="pullquote-center evs-project-quote">
						<?php echo esc_html( $evs_project_data['highlight_quote'] );?>
					</div>
					<?php endif ?>
					<hr>
				</header>
				<div class="row">
					<div class="col-md-7">
						<div class="row">
							<div class="col-xs-3">
								<p><b><?php esc_html_e( 'Soort werk', 'siw' );?></b></p>
							</div>
							<div class="col-xs-9">
								<?php echo esc_html( $evs_project_data['soort_werk'] );?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<p><b><?php esc_html_e( 'Locatie', 'siw' );?></b></p>
							</div>
							<div class="col-xs-9">
								<?php echo esc_html( sprintf( '%s, %s', $evs_project_data['plaats'], $evs_project_data['land'] ) );?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<p><b><?php esc_html_e( 'Tijdsduur', 'siw' ); ?></b></p>
							</div>
							<div class="col-xs-9">
								<?php echo ucfirst( esc_html( $evs_project_data['projectduur'] ) ); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<p><b><?php esc_html_e( 'Deadline', 'siw' );?></b></p>
							</div>
							<div class="col-xs-9">
								<?php echo esc_html( $evs_project_data['deadline'] );?>
							</div>
						</div>
						<?php echo do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
						<?php the_post_thumbnail();?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action( 'siw_evs_project_footer' );?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>

<?php get_footer(); ?>