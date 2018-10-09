<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bevat informatie over een continent
 * 
 * @package 	SIW\Reference data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Continent {

    /**
     * Slug van continent
     *
     * @var string
     */
    protected $slug;

    /**
     * Naam van het continent
     *
     * @var string
     */
    protected $name;

    /**
     * Kleurcode van continent op kaart
     *
     * @var string
     */
    protected $color;


    /**
     * Constructor
     */
    public function __construct( $continent ) {
        $defaults = [
            'slug'  => '',
            'name'  => '',
            'color' => ','
        ];
        $continent = wp_parse_args( $continent, $defaults );
        $this->set_slug( $continent['slug'] );
        $this->set_name( $continent['name'] );
        $this->set_color( $continent['color'] );
    }

    /**
     * Zet slug van continent
     *
     * @param string $slug
     * @return void
     */
    public function set_slug( $slug ) {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Haal de slug van het continent op
     *
     * @return string
     */
    public function get_slug() {
        return $this->slug;
    }

    /**
     * Zet naam van continent
     *
     * @param string $name
     * @return void
     */
    public function set_name( $name ) {
        $this->name = $name;
        return $this;
    }

    /**
     * Haal de naam van het continent op
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Zet kleurcode van continent op kaart
     *
     * @param string $color
     * @return void
     */
    public function set_color( $color ) {
        $this->color = $color;
        return $this;
    }

    /**
     * Haal kleurcode van continent op kaart op
     *
     * @return string
     */
    public function get_color() {
        return $this->color;
    }

    /**
     * Haal alle landen van een continent op
     *
     * @return array
     */
    public function get_countries() {
        //TODO: get_countries_by_continent
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function has_countries() {

    }

}