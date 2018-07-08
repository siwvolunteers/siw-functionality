<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/

/**
 * Abstracte klasse voor import uit Plato
 */
abstract class SIW_Plato_Import {

    /**
     * Naam van import (voor logging)
     *
     * @var string
     */
    protected $name;

    /**
     * Organization webkey van plato
     *
     * @var string
     */
    protected $webkey;

    /**
     * Endpoint voor import
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Endpoint url voor Plato
     *
     * @var string
     */
    protected $endpoint_url;

    /**
     * Ruwe xml response van Plato
     *
     * @var SimpleXML
     */
    protected $xml;

    /**
     * WooCommerce-logger
     *
     * @var WC_Logger
     */
    protected $logger;

    /**
     * Context voor logger
     *
     * @var array
     */
    protected $logger_context;

    /**
     * Name van background process
     *
     * @var string
     */
    protected $process_name;

    /**
     * Data voor background process
     *
     * @var array
     */
    protected $data = array();

    /**
     * Undocumented function
     */
    public function __construct() {
        $this->set_logger();
        $this->set_webkey();
        $this->set_endpoint_url();
    }

    /**
     * Zet Plato-webkey
     *
     * @return void
     */
    private function set_webkey() {
        $this->webkey = siw_get_setting( 'plato_organization_webkey' );
    }

    /**
     * Haal webkey op
     *
     * @return void
     */
    protected function get_webkey() {
        return $this->webkey;
    }


    /**
     * Zet URL van het endpoint
     *
     * @return void
     */
    private function set_endpoint_url() {
        $this->endpoint_url = SIW_PLATO_WEBSERVICE_URL . $this->endpoint;
        $this->endpoint_url = add_query_arg( 'organizationWebserviceKey', $this->webkey, $this->endpoint_url );
    }
    

    /**
     * Zet (WooCommerce-)logger
     *
     * @return void
     */
    private function set_logger() {
        $this->logger = wc_get_logger();
        $source = str_replace( ' ', '-', 'siw-' . $this->name . '-' .  date( 'Y-m-d' ) ); //TODO:sprintf
        $this->logger_context = array( 'source' => $source );
    }


    /**
     * Schrijf naar log
     *
     * @param string $level
     * @param string $message
     * @return void
     */
    public function log( $level, $message ) {
        $this->logger->log( $level, $message, $this->logger_context );
    }


    /**
     * Haal de XML op
     *
     * @return void
     */
    protected function retrieve_xml() {
    
        $args = array(
            'timeout'	=> 60,
        );
        $response = wp_safe_remote_get( $this->endpoint_url, $args );

        /* In het geval van een fout: foutmelding wegschrijven naar log */
        if ( is_wp_error( $response ) ) {
            $this->log('error', 'Verbinding met PLATO mislukt. Response: ' . wc_print_r( $response ), $context );
            return false;
        }

        /* Zoek HTML-statuscode en breek af indien ongelijk aan 200 */
        $status_code = wp_remote_retrieve_response_code( $response );
        if ( '200' != $status_code ) {
            $this->log( 'error', 'Verbinding met PLATO mislukt. Statuscode: ' . $status_code );
            return false;
        }

        $this->xml = simplexml_load_string( wp_remote_retrieve_body( $response ) );
        return true;
    }

    /**
     * Verwerk de XML
     *
     * @return void
     */
    abstract protected function process_xml();


    /**
     * Voer de Plato-import uit
     *
     * @return array
     */
    public function run() {
        //Start import
        $this->log( 'info', sprintf( 'Start %s', $this->name ) );

        if ( ! $this->retrieve_xml() ) {
            return;
        }
        $this->process_xml();
        
        //Eind import
        $this->log( 'info', sprintf( 'Eind %s', $this->name ) );

        return $this->data;
    }
    
}