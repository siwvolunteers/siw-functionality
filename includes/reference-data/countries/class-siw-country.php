<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bevat informatie over een land
 * 
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Country {

	/**
	 * ISO-code van het land
	 *
	 * @var string
	 */
	protected $iso_code;

	/**
	 * Naam van het land
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Slug van het land
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Continent van het land
	 *
	 * @var SIW_Continent
	 */
	protected $continent;

	/**
	 * Geeft aan of het land toegestaan is
	 *
	 * @var boolean
	 */
	protected $allowed;

	/**
	 * Geeft aan of het land groepsprojecten heeft
	 *
	 * @var boolean
	 */
	protected $has_workcamps;

	/**
	 * Geeft aan of het land ESC-projecten heeft
	 *
	 * @var boolean
	 */
	protected $has_esc_projects;

	/**
	 * Geeft aan of het land Op Maat projecten heeft
	 *
	 * @var boolean
	 */
	protected $has_tailor_made_projects;

	/**
	 *  Eigenschappen van land voor kaart van de wereld
	 *
	 * @var stdClass
	 */
	protected $world_map_data;

	/**
	 * Eigenschappen van land voor kaart van Europa
	 *
	 * @var stdClass
	 */
	protected $europe_map_data;

	/**
	 * Constructor
	 *
	 * @param array $country
	 */
	public function __construct( $country ) {

		$defaults = [
			'iso'         => '',
			'slug'        => '',
			'name'        => '',
			'continent'   => '',
			'allowed'     => true,
			'workcamps'   => false,
			'tailor_made' => false,
			'esc'         => false,
			'world_map'   => [],
			'europe_map'  => [],
		];
		$country = wp_parse_args( $country, $defaults );

		// Zet eigenschappen
		$this->set_iso_code( $country['iso'] );
		$this->set_slug( $country['slug'] );
		$this->set_name( $country['name'] );
		$this->set_continent( $country['continent'] );
		$this->set_allowed( $country['allowed'] );
		$this->set_has_workcamps( $country['workcamps'] );
		$this->set_has_tailor_made_projects( $country['tailor_made'] );
		$this->set_has_esc_projects( $country['esc'] );
		$this->set_world_map_data( $country['world_map'] );
		$this->set_europe_map_data( $country['europe_map']);
	}

	/**
	 * Zet ISO-code van het land
	 *
	 * @param string $slug
	 * @return $this
	 */
	public function set_iso_code( $iso_code ) {
		$this->iso_code = $iso_code;
		return $this;
	}

	/**
	 * Geeft ISO-code van het land terug
	 *
	 * @return string
	 */
	public function get_iso_code() {
		return $this->iso_code;
	}

	/**
	 * Zet slug van land
	 *
	 * @param string $slug
	 * @return $this
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * Geeft slug van het land terug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Zet naam van land
	 *
	 * @param string $name
	 * @return $this
	 */
	public function set_name( $name ) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Geeft naam van land terug
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Zet continent van het project
	 *
	 * @param SIW_Continent|string $continent
	 * @return $this
	 */
	public function set_continent( $continent ) {
		if ( ! is_a( $continent, 'SIW_Continent' ) ) {
			$continent = siw_get_continent( $continent );
		}
		$this->continent = $continent;
		return $this;
	}

	/**
	 * Geeft continent van land terug
	 *
	 * @return SIW_Continent
	 */
	public function get_continent() {
		return $this->continent;
	}

	/**
	 * Zet of land toegestaan is
	 *
	 * @param bool $allowed
	 * @return $this
	 */
	public function set_allowed( $allowed ) {
		$this->allowed = (bool) $allowed;
		return $this;
	}

	/**
	 * Geeft aan of land toegestaan is
	 *
	 * @return boolean
	 */
	public function is_allowed() {
		return $this->allowed;
	}

	/**
	 * Zet of het land groepsprojecten heeft
	 *
	 * @param bool $has_workcamps
	 * @return $this
	 */
	public function set_has_workcamps( $has_workcamps ) {
		$this->has_workcamps = (bool) $has_workcamps;
		return $this;
	}

	/**
	 * Geeft aan of het land groepsprojecten heeft
	 *
	 * @return bool
	 */
	public function has_workcamps() {
		return $this->has_workcamps;
	}

	/**
	 * Zet of het land ESC-projecten heeft
	 *
	 * @param bool $has_esc_projects
	 * @return $this
	 */
	public function set_has_esc_projects( $has_esc_projects ) {
		$this->has_esc_projects = (bool) $has_esc_projects;
		return $this;
	}

	/**
	 * Geeft aan of het land ESC-projecten heeft
	 *
	 * @return boolean
	 */
	public function has_esc_projects() {
		return $this->has_esc_projects;
	}

	/**
	 * Zet of het land Op Maat projecten heeft
	 *
	 * @param bool $has_tailor_made_projects
	 * @return $this
	 */
	public function set_has_tailor_made_projects( $has_tailor_made_projects ) {
		$this->has_tailor_made_projects = (bool) $has_tailor_made_projects;
		return $this;
	}

	/**
	 * Geeft aan of het land Op Maat projecten heeft
	 *
	 * @return boolean
	 */
	public function has_tailor_made_projects() {
		return $this->has_tailor_made_projects;
	}

	/**
	 * Zet de gegevens van het land voor de kaart van de wereld
	 *
	 * @param array $world_map_data
	 * @return $this
	 */
	public function set_world_map_data( $world_map_data ) {
		$this->world_map_data = (object) $world_map_data;
		return $this;
	}

	/**
	 * Geeft de gegevens van het land voor de kaart van de wereld terug
	 *
	 * @return stdClass
	 */
	public function get_world_map_data() {
		return (object) $this->world_map_data;
	}
	/**
	 * Zet de gegevens van het land voor de kaart van Europa
	 * 
	 * @param array $europe_map_data
	 * @return $this
	 */
	public function set_europe_map_data( $europe_map_data ) {
		$this->europe_map_data = (object) $europe_map_data;
		return $this;
	}
	
	/**
	 * Geeft de gegevens van het land voor de kaart van Europa terug
	 *
	 * @return stdClass
	 */
	public function get_europe_map_data() {
		return (object) $this->europe_map_data;
	}

	/**
	 * Geeft regiospecialist van het land terug
	 *
	 * @return WP_User
	 */
	public function get_specialist() {

		$specialist_id = siw_get_setting( $this->slug . '_regiospecialist' );
		
		if ( ! is_int( $specialist_id ) ) {
			return false;
		}

		$specialist = get_user_by( 'id', $specialist_id );
		if ( ! is_a( $specialist, 'WP_User' ) ) {
			return false;
		}

		return $specialist;
	}
}
