<?php global $post, $pinnacle;
?>
<div id="post-<?php the_ID(); ?>" class="blog_item kt_item_fade_in postclass kad_blog_fade_in grid_item" itemscope="" itemtype="http://schema.org/BlogPosting">
	<div class="postcontent">
		<header>
			<a href="<?php the_permalink() ?>"><?php echo '<h4 class="entry-title" itemprop="name headline">';  the_title(); echo '</h4>'; ?></a>
			<?php get_template_part('templates/entry', 'meta-subhead'); ?>
		</header>
		<div class="entry-content" itemprop="description articleBody">
			<?php the_excerpt(); ?>
			<a class="read-more" href="<?php the_permalink() ?>"><?php esc_html_e('Lees meer', 'siw');?></a>
		</div>
		<footer class="single-footer clearfix">
			<?php $categories= get_the_terms($post->ID,'soort_vacature');
			if ( $categories && ! is_wp_error( $categories ) ){
				$vacature_categories = array();
				foreach ( $categories as $category ) {
					$vacature_categories[] = $category->name;
				}
				$vacature_category = join( ", ", $vacature_categories );
			}
			if ( $vacature_category ) {?>
				<span class="postedinbottom"><i class="kt-icon-tag4"></i> <?php echo $vacature_category; ?></span><?php
			}?>
		</footer>
	</div>
</div>
