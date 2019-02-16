<?php
/*
Template Name: Agenda
*/
?>
	<?php get_header(); ?>
	<?php get_template_part('templates/page', 'header'); ?>

	<div id="content" class="container">
		<div class="row">
			<?php $fullclass = 'fullwidth'; global $post; $postclass = 'postlist'; ?>
		<div class="main <?php echo kadence_main_class();?> <?php echo esc_attr( $postclass ) .' '. esc_attr( $fullclass ); ?>" role="main">
		<div class="entry-content" itemprop="mainContentOfPage">
			<?php get_template_part('templates/content', 'page'); ?>
		</div>
			<?php  global $post, $pinnacle; $blog_category = get_post_meta( $post->ID, '_kad_blog_cat', true );
 				$meta_query = [
					'relation' => 'AND',
					[
						'key'     => 'siw_agenda_eind',
						'value'   => time(),
						'compare' => '>=',
					]
				];
					$temp = $wp_query;
					$wp_query = null;
					$wp_query = new WP_Query();
					$wp_query->query([
						'paged'          => $paged,
						'post_type'      => 'agenda',
						'posts_per_page' => -1,
						'meta_key'       => 'siw_agenda_start',
						'orderby'        => 'meta_value_num',
						'order'          => 'ASC',
						'meta_query'     => $meta_query,
						]
					);
					$count = 0;
					if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
						$count++;
						include( 'content-agenda.php' );
					endwhile; else: ?>
					<div>
						<h5 class="error-not-found"><?php _e( 'Er zijn momenteel geen geplande evenementen.', 'siw' ); ?></h5>
					</div>
					<?php endif; ?>
				<?php
				$wp_query = null; $wp_query = $temp;
				wp_reset_query();
?>
<?php do_action('kt_after_pagecontent'); ?>
</div><!-- /.main -->
<?php get_footer(); ?>