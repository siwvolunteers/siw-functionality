<?php global $post, $pinnacle;

	$event_data = siw_get_event_data( $post->ID );
	$loadscripts = ( 1 == $count ) ? true : false;
	$location_map = sprintf('[gmap address="%s, %s %s" title="%s" zoom="13" maptype="ROADMAP" loadscripts="%s"]', esc_attr( $event_data['address'] ), esc_attr( $event_data['postal_code'] ), esc_attr( $event_data['city'] ), esc_attr( $event_data['location'] ), esc_attr( $loadscripts) );

?>
<article id="agenda-<?php the_ID(); ?>" <?php post_class('kad_blog_item postclass kad-animation'); ?> data-animation="fade-in" data-delay="0" itemscope="" itemtype="http://schema.org/BlogPosting">
	<div class="row">
		<div class="col-md-4">
			<?php echo do_shortcode( $location_map ); ?>
		</div>
		<div class="col-md-8 postcontent">
			<header>
				<a href="<?php the_permalink() ?>" rel="bookmark" class="url">
				<?php echo '<h2 class="entry-title" itemprop="name headline">';  the_title(); echo '</h2>'; ?>
				<h4><?php echo esc_html( $event_data['duration'] );?></h4>
				</a>
			</header>
			<div class="entry-content" itemprop="articleBody">
			<p class="agenda-location">
				<?php echo esc_html( $event_data['location'] ) . '<br/>'. esc_html( $event_data['address'] ) . '<br/>' . esc_html( $event_data['postal_code'] . ' ' .  $event_data['city'] ) ; ?>
			</p>
			<?php the_excerpt(); ?>
			<a class="read-more" href="<?php the_permalink() ?>"><?php esc_html_e( 'Lees meer', 'siw' );?></a>
			</div>
		</div>
		<div class="col-md-12 postfooterarea">
		<footer class="single-footer clearfix">
			<?php $categories= get_the_terms( $post->ID, 'soort_evenement');
			if ( $categories && ! is_wp_error( $categories ) ){
				$agenda_categories = array();
				foreach ( $categories as $category ) {
					$agenda_categories[] = $category->name;
				}
				$agenda_category = join( ", ", $agenda_categories );
			}
			if ( isset( $agenda_category ) ) {?>
				<span class="postedinbottom"><i class="kt-icon-tag4"></i> <?php echo esc_html( $agenda_category ); ?></span><?php
			}?>
		</footer>
		</div>
	</div><!-- row-->
</article> <!-- Article -->
