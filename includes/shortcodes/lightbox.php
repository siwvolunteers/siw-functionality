<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/*
 * Lightbox met inhoud van speciale pagina
 */
add_shortcode( 'siw_pagina_lightbox', function( $atts ) {
	extract( shortcode_atts( array(
		'link_tekst' => '',
		'pagina' 	 => '',
		), $atts, 'siw_pagina_lightbox' )
	);

	$pages = array(
		'kinderbeleid' => 'child_policy',
	);
	/* Haal pagina id op en breek af als pagina niet ingesteld is */
	$page_id = siw_get_setting( $pages[ $pagina ] . '_page' );
	if ( empty( $page_id ) ) {
		return;
	}

	/* Haal titel en inhoud van de pagina op */
	$page_title = get_the_title( $page_id );
	$page_content = get_post_field('post_content', $page_id );


	/* Start template*/
	ob_start();	?>

 	<a data-toggle="modal" href="#" data-target="#siw-page-<?php echo esc_attr( $page_id );?>-modal"><?php echo esc_html( $link_tekst ); ?></a>
	<div class="modal fade" id="siw-page-<?php echo esc_attr( $page_id );?>-modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo esc_html( $page_title );?></h4>
				</div>
				<div class="modal-body">
				<?php echo wp_kses_post( wpautop( $page_content ) ); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default kad-btn" data-dismiss="modal"><?php esc_html_e('Sluiten', 'siw');?></button>
				</div>
			</div>
		</div>
	</div>

	<?php
	$output = ob_get_clean();
	return $output;
});
