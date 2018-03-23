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
	$page_id = siw_get_translated_page_id( $page_id );
	$GLOBALS['lightboxes'][] = $page_id;

	/* HTML voor lightbox aan footer toeoegen */
	add_action( 'wp_footer', function() {
 		$lightboxes = array_unique( $GLOBALS['lightboxes'] );
		foreach( $lightboxes as $page_id ) {
			/* Haal titel en inhoud van de pagina op */
			$page_title = get_the_title( $page_id );
			$page_content = get_post_field('post_content', $page_id );
			?>
			<div class="modal fade" id="siw-page-<?php echo esc_attr( $page_id );?>-modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><?php echo esc_html( $page_title );?></h4>
						</div>
						<div class="modal-body">
						<?php echo wp_kses_post( wpautop( do_shortcode( $page_content ) ) ); ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default kad-btn" data-dismiss="modal"><?php esc_html_e('Sluiten', 'siw');?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	});

	$link = sprintf('<a data-toggle="modal" href="#" data-target="#siw-page-%s-modal">%s</a>', esc_attr( $page_id ), esc_html( $link_tekst ));

	return $link;
});

siw_add_shortcode( 'siw_pagina_lightbox', array(
	'title' => '[SIW] - ' . __( 'Pagina-lightbox', 'siw' ),
	'attr'  => array(
		'link_tekst' => array(
			'type'  => 'text',
			'title' => __( 'Link tekst', 'siw' ),
		),
		'pagina' => array(
			'type'    => 'select',
			'title'   => __( 'Pagina', 'siw' ),
			'default' => '',
			'values'  => array(
				'kinderbeleid' => __( 'Beleid kinderprojecten', 'siw' ),
			),
		),
	),
));
