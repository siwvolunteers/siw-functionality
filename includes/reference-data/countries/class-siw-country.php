<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bevat informatie over een land
 * 
 * @package   SIW\Reference-Data
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
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

		$this->iso_code = $country['iso'];
		$this->slug = $country['slug'];
		$this->name = $country['name'];
		$this->continent = siw_get_continent( $country['continent'] );
		$this->allowed = $country['allowed'];
		$this->has_workcamps = $country['workcamps'];
		$this->has_tailor_made_projects = $country['tailor_made'];
		$this->has_esc_projects = $country['esc'];
		$this->world_map_data = (object) $country['world_map'];
		$this->europe_map_data = (object) $country['europe_map'];
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
	 * Geeft slug van het land terug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
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
	 * Geeft continent van land terug
	 *
	 * @return SIW_Continent
	 */
	public function get_continent() {
		return $this->continent;
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
	 * Geeft aan of het land groepsprojecten heeft
	 *
	 * @return bool
	 */
	public function has_workcamps() {
		return $this->has_workcamps;
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
	 * Geeft aan of het land Op Maat projecten heeft
	 *
	 * @return boolean
	 */
	public function has_tailor_made_projects() {
		return $this->has_tailor_made_projects;
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

		$specialist_id = siw_get_option( $this->slug . '_specialist' );
		
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
