<?php global $post, $pinnacle;
?>

<div id="post-<?php the_ID(); ?>" class="blog_item postclass grid_item" itemscope="" itemtype="http://schema.org/BlogPosting">
<a href="<?php the_permalink() ?>">
	<div class="postcontent">
		<header>
			<?php
				$images = siw_meta( 'image', ['limit' => 1 ] );
				$image = reset( $images );
				echo wp_get_attachment_image( $image['ID'], ['300', '200'], false, [ 'data-no-lazy' => true ] ); ?>
			<b><?php echo '<h5 class="entry-title" itemprop="name headline">';  the_title(); echo '</h5>'; ?></b>
		</header>
		<div class="entry-content" itemprop="description articleBody">
			<?php echo esc_html( rwmb_get_value( 'quote' )); ?>
		</div>
		<footer class="single-footer clearfix">
			<?php
			$term = siw_meta( 'continent');
			?>
				<span class="postedinbottom"><?php echo \SIW\Formatting::generate_icon( 'siw-icon-globe' );?></i>&nbsp;<?php echo esc_html( $term->name );?></span><?php
			?>
		</footer>
	</div>
	</a>
</div>

