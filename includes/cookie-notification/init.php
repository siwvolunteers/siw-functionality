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
			<div class="wrap">
				<div class="cookie-text"><?php
					esc_html_e( 'We gebruiken cookies om er voor te zorgen onze website optimaal werkt werkt en om het gebruik van onze website te analyseren.', 'siw' ); echo SPACE;
					esc_html_e( 'Door gebruik te blijven maken van onze website, ga je hiermee akkoord.', 'siw' ); ?>
				</div>
				<div class="cookie-button">
					<button id="siw-cookie-consent" class="button"><?php esc_html_e( 'Akkoord', 'siw' ); ?></button>
				</div>
			</div>
	</div>
<?php
});
