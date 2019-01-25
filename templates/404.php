<?php get_header();?>
<div id="content" class="siw-404">
	<div class="row">
		<div class="main <?php echo kadence_main_class(); ?>" role="main">
			<h4 class="sectiontitle"><?php esc_html_e( 'Pagina niet gevonden', 'siw'); ?></h4>
			<p>
			<?php esc_html_e( 'Oeps! Helaas kunnen we de pagina die je zoekt niet vinden. Controleer of de spelling correct is en doe nog een poging via onderstaande zoekfunctie.', 'siw' ); ?>
			</p>
			<div class="search_form_404"><?php get_search_form(); ?></div>
			<p>
			<?php printf( wp_kses_post( __('Het kan ook zijn dat de pagina die je zoekt niet (meer) beschikbaar is. In dat geval: ga terug naar de <a href="%s">homepagina</a> of geniet nog even van deze vrolijke foto van onze Franse partnerorganisatie Concordia France.', 'siw' ) ), esc_url( get_site_url() ) );?>
			</p>
		</div>
<?php get_footer(); ?>