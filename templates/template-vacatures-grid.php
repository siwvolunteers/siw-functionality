<?php
/*
Template Name: Vacatures Grid
*/
?>
<?php get_header(); ?>
<?php get_template_part('templates/page', 'header'); ?>
<div id="content" class="container">
	<div class="row">
			<?php global $post ;
			$introduction = get_post_meta( $post->ID, 'siw_vacatures_introduction', true );
			$open_application = get_post_meta( $post->ID, 'siw_vacatures_open_application', true );
			$open_application_email = antispambot( get_post_meta( $post->ID, 'siw_vacatures_open_application_email', true ) );
			$no_jobs = get_post_meta( $post->ID, 'siw_vacatures_no_jobs', true );
			?>
		<div class="main <?php echo kadence_main_class();?>" role="main">
			<div class="pageclass entry-content" itemprop="mainContentOfPage">
				<div class="row">
					<div class="col-md-9">
						<h3><?php esc_html_e( 'Vacatures', 'siw' );?></h3>
						<p>
						<?php echo esc_html( $introduction );?>
						</p>
						<div id="kad-blog-grid" class="rowtight init-isotope siw-vacature-grid" data-fade-in="1" data-iso-selector=".b_item" data-iso-style="masonry" data-iso-filter="false">
						<?php
						$meta_query = [
							'relation' => 'AND',
							[
								'key'     => 'siw_vacature_deadline',
								'value'   => time(),
								'compare' => '>=',
							]
						];
						$temp = $wp_query;
						$wp_query = null;
						$wp_query = new WP_Query();
						$wp_query->query( [
							'paged'          => false,
							'post_type'      => 'vacatures',
							'posts_per_page' => -1,
							'meta_query'     => $meta_query,
							]
						);
						$count = 0;
						if ( $wp_query->have_posts() ):
							while ( $wp_query->have_posts() ){
								$wp_query->the_post();?>
								<div class="tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12 b_item kad_blog_item">
									<?php include('content-vacature-grid.php');?>
								</div><?php
							}
						?>
						</div><?php
						else:?>
						</div>
						<div class="well">
							<p>
								<em><?php echo esc_html( $no_jobs ); ?></em>
							</p>
						</div>
						<?php endif ?>
						<?php $wp_query = null;$wp_query = $temp; // Reset ?>
						<?php wp_reset_query(); ?>
					</div>
					<div class="col-md-3">
						<h3><?php esc_html_e('Open sollicitatie', 'siw');?></h3>
						<p>
						<?php printf( wp_kses_post( $open_application . ' ' . __( 'Stuur jouw motivatie en curriculum vitae onder vermelding van \'Open sollicitatie\' naar  <a class="email" href="mailto:%s">%s</a>', 'siw' ) ), $open_application_email, $open_application_email );?>
						</p>
					</div>
				</div>
		<?php do_action('kt_after_pagecontent'); ?>
	</div><!-- /.main -->
<?php get_footer(); ?>