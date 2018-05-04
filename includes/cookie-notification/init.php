<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer', function() {
?>
	<div id="siw-cookie-notification">
		<div class="container">
			<div class="row">
				<div class="col-md-10 cookie-text"><?php
					esc_html_e( 'We gebruiken cookies om ervoor te zorgen dat onze website optimaal werkt en om het gebruik van onze website te analyseren.', 'siw' ); echo SPACE;
					esc_html_e( 'Door gebruik te blijven maken van onze website, ga je hiermee akkoord.', 'siw' ); ?>
				</div>
				<div class="col-md-2 cookie-button">
					<button id="siw-cookie-consent" class="button"><?php esc_html_e( 'Ik ga akkoord!', 'siw' ); ?></button>
				</div>
			</div>
		</div>
	</div>
<?php
});
