<?php get_header();?>
<div id="content" class="siw-newsletter-confirmation">
	<div class="row">
		<div class="main <?php echo kadence_main_class(); ?>" role="main">
			<h4 class="sectiontitle"><?php esc_html_e( 'Aanmelding nieuwsbrief', 'siw'); ?></h4>
			<p>
				<?php do_action( 'siw_newsletter_confirmation' );?>
			</p>
		</div>
<?php get_footer(); ?>