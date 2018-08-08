<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */


add_shortcode( 'siw_zoekresultaten', function() {

    $url = wc_get_page_permalink( 'shop' );
    $text = __( 'Bekijk alle projecten', 'siw' );

    /* Verwerk zoekargument bestemming*/
    $category_arg   = '';
    $category_slug  = sanitize_key( get_query_var( 'bestemming', false ) );
    $category       = get_term_by( 'slug', $category_slug, 'product_cat' );

   
    if ( is_a( $category, 'WP_Term') ) {   
        $category_arg = sprintf( 'category="%s"', $category_slug );
        $url = get_term_link( $category->term_id );
        $text .= SPACE . sprintf( __( 'in %s', 'siw' ), $category->name );
    }

    /* Verwerk zoekargument maand*/
    $month_arg  = '';    
    $month_slug = sanitize_key( get_query_var( 'maand', false ) );   
    $month      = get_term_by( 'slug', $month_slug, 'pa_maand');
    $month_id   = $month->term_id; 
    if ( is_a( $month, 'WP_Term') ) {
        $month_arg  = sprintf( 'attribute="maand" terms="%s"', $month_id );
        $url        = add_query_arg( 'filter_maand', $month_slug, $url );
        $text       .= SPACE . sprintf( __( 'in %s', 'siw' ), strtolower( $month->name ) );        
    }

    /* Genereer output */
    $output = __( 'Met een Groepsproject ga je voor 2 tot 3 weken naar een project, de begin- en einddatum van het project staan al vast.', 'siw' ) . SPACE;
    $output .= __( 'Hieronder zie je een selectie van de mogelijkheden', 'siw' );
    $output .= do_shortcode( sprintf( '[products limit="6" columns="3" orderby="random" visibility="visible" %s %s]', $category_arg, $month_arg ) );
    $output .= '<div style="text-align:center">';
    $output .= sprintf( '<a href="%s" class="kad-btn kad-btn-primary">%s</a>', esc_url( $url ), esc_html( $text ) );
    $output .= '</div>';

    return $output;
});
