<?php 
global $pinnacle, $postcolumn; 

	get_header(); 

	get_template_part('templates/page', 'header'); 
	$itemsize = SIW_CSS::generate_responsive_class( 3, 6, 12 );
	?>
	<div id="content" class="container">
		<div class="row">
			<div class="main <?php echo kadence_main_class(); ?> postlist fullwidth" role="main">
				<div id="kad-blog-grid-archive" class="rowtight kad-blog-grid init-isotope-siw" data-fade-in="1" data-isotope='{ "itemSelector": ".b_item", "layoutMode": "fitRows" }'>
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