<?php 
global $pinnacle, $postcolumn; 

	get_header(); 

	get_template_part('templates/archive', 'header'); 

	$postclass = 'postlist';
	$blog_grid_column = '4';
	if ( $blog_grid_column == '3' ) {
		$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
		$postcolumn = '3';
	} else {
		$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
		$postcolumn = '4';
	}
	?>
	<div id="content" class="container">
	<div class="container">
			<div class="row siw-archive-intro">
				<div class="md-12">
					<?php //TODO: verplaatsen naar after_page_header hook
					echo SIW_Formatting::array_to_text( [
						esc_html__( 'Hieronder zie je de landenpagina’s van de Projecten op Maat.', 'siw' ),
						esc_html__( 'Per land leggen we uit welke type projecten wij aanbieden.', 'siw' ),
						esc_html__( 'Tijdens onze Projecten Op Maat bepaal je samen met een regiospecialist wat je gaat doen en hoe lang jouw project duurt.', 'siw' ),
						sprintf( esc_html__( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina Projecten Op Maat.', 'siw' ), 'hoi'), //TODO:link
					]);?>
				</div>
			</div>
		</div>

		<?php
			if ( is_post_type_archive( 'siw_tm_country' ) ) {
				echo SIW_Formatting::generate_filter_buttons( 'siw_tm_country_continent');
			}
		?>
		<div class="row">
			<div class="main <?php echo kadence_main_class(); ?> postlist fullwidth" role="main">
				<div id="kad-blog-grid-archive" class="rowtight kad-blog-grid init-isotope" data-fade-in="1"  data-iso-selector=".b_item" data-iso-style="masonry">
					<?php while ( have_posts()) : the_post(); ?>
					<?php $continent = rwmb_meta( 'continent');?>
					<div class="<?php echo esc_attr( $itemsize );?> b_item siw_grid_item kad_blog_item <?php echo esc_attr( $continent->slug);?>">
						<?php include('content-tm_country.php');?>
					</div>
					<?php endwhile; ?>
				</div>
			<?php
			do_action('kt_after_pagecontent'); ?>
			</div><!-- /.main -->
<?php get_footer(); ?>