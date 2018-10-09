<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Genereer structured data voor evenement
 *
 * @param array $event
 * @return string
 */
function siw_generate_event_json_ld( $event ) {

    //TODO: standaard afbeelding voor infodag -> setting

    $data = array(
        '@context'      => 'http://schema.org',
        '@type'         => 'event',
        'name'          => esc_attr( $event['title'] ),
        'description'   => esc_attr( $event['excerpt'] ),
        'image'         => esc_url( $event['post_thumbnail_url'] ),
        'startDate'     => esc_attr( $event['start_date'] ),
        'endDate'       => esc_attr( $event['end_date'] ),
        'url'           => esc_url( $event['permalink'] ),
        'location'      => array(
            '@type'     => 'Place',
            'name'      => esc_attr( $event['location'] ),
            'address'   => esc_attr( sprintf('%s, %s %s', $event['address'], $event['postal_code'], $event['city'] ) ),
        ),
    );
   
   return siw_generate_json_ld( $data );
}


/**
 * Genereer structured data voor evenement
 *
 * @param array $job
 * @return string
 */
function siw_generate_job_json_ld( $job ) {

    $description = wpautop( $job['inleiding'] ) .
        '<h5><strong>' . __( 'Wat ga je doen?', 'siw' ) . '</strong></h5>' . wpautop( $job['wat_ga_je_doen'] . siw_generate_list( $job['wat_ga_je_doen_lijst'] ) ) .
        '<h5><strong>' . __( 'Wie ben jij?', 'siw' ) . '</strong></h5>' . wpautop( $job['wie_ben_jij'] . siw_generate_list( $job['wie_ben_jij_lijst'] ) ) .
        '<h5><strong>' . __( 'Wat bieden wij jou?', 'siw' ) . '</strong></h5>' . wpautop( $job['wat_bieden_wij_jou'] . siw_generate_list( $job['wat_bieden_wij_jou_lijst'] ) ) .
        '<h5><strong>' . __( 'Wie zijn wij?', 'siw' ) . '</strong></h5>' . wpautop( siw_get_setting('company_profile') );

    $logo = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );

    $data = array(
        '@context'          => 'http://schema.org',
        '@type'             => 'JobPosting',
        'description'       => wp_kses_post( $description ),
        'title'             => esc_attr( $job['title'] ),
        'datePosted'        => esc_attr( $job['date_last_updated'] ),
        'validThrough'      => esc_attr( $job['deadline_datum'] ),
        'employmentType'    => array( 'VOLUNTEER', 'PARTTIME'),
        'hiringOrganization'=> array(
            '@type' => 'Organization', 
            'name'  => SIW_NAME,
            'sameAs'=> SIW_SITE_URL,
            'logo'  => esc_url( $logo ),
        ),
        'jobLocation'   => array(
            '@type'     => 'Place',
            'address'   => array(
                '@type'             => 'PostalAddress',
                'streetAddress'     => SIW_ADDRESS,
                'addressLocality'   => SIW_CITY,
                'postalCode'        => SIW_POSTAL_CODE,
                'addressRegion'     => SIW_CITY,
                'addressCountry'    => 'NL',
            ),
        ),        
    );

    return siw_generate_json_ld( $data );
}


/**
 * Hulpfunctie om script-tag met json-LD te genereren
 *
 * @param array $data
 * @return string
 */
function siw_generate_json_ld( $data ) {
    ob_start();
    ?>
    <script type="application/ld+json">
    <?php echo json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);?>
    </script>
    <?php
    $json_ld = ob_get_clean();
    return $json_ld;
}
