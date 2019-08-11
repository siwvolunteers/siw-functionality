<?php

/**
 * Bevat informatie over een sociaal netwerk
 * 
 * @package     SIW\Data
 * @copyright   2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Data_Social_Network {

	/**
	 * Slug van het netwerk
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Naam van het netwerk
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * CSS-class van icoon
	 *
	 * @var string
	 */
	protected $icon_class;

	/**
	 * URL van netwerk om te volgen
	 *
	 * @var string
	 */
	protected $follow_url;

	/**
	 * URL-template voor delen
	 *
	 * @var string
	 */
	protected $share_url_template;

	/**
	 * Is netwerk om te delen?
	 *
	 * @var bool
	 */
	protected $share;

	/**
	 * Is netwerk om te volgen
	 *
	 * @var bool
	 */
	protected $follow;

	/**
	 * Constructor
	 *
	 * @param array $network
	 */
	public function __construct( array $network ) {
		$defaults = [
			'slug'               => '',
			'name'               => '',
			'icon_class'         => '',
			'follow'             => false,
			'follow_url'         => null,
			'share'              => false,
			'share_url_template' => null
		];
		$network = wp_parse_args( $network, $defaults );

		$this->slug = $network['slug'];
		$this->name = $network['name'];
		$this->icon_class = $network['icon_class'];
		$this->follow = $network['follow'];
		$this->follow_url = $network['follow_url'];
		$this->share = $network['share'];
		$this->share_url_template = $network['share_url_template'];
	}

	/**
	 * Geeft slug van netwerk terug
	 * 
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Geeft de naam van het netwerk terug
	 * 
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Geeft icon class voor voor netwerk terug
	 * 
	 * @return string
	 */
	public function get_icon_class() {
		return $this->icon_class;
	}

	/**
	 * Geeft aan of via dit netwerk gedeeld kan worden
	 *
	 * @return boolean
	 */
	public function is_for_sharing() {
		return $this->share;
	}

	/**
	 * Geeft aan of dit netwerk gevolgd kan worden
	 *
	 * @return boolean
	 */
	public function is_for_following() {
		return $this->follow;
	}

	/**
	 * Geeft URL van network om te volgen terug
	 *
	 * @return string
	 */
	public function get_follow_url() {
		return $this->follow_url;
	}

	/**
	 * Geeft template voor url om te delen terug
	 *
	 * @return string
	 */
	public function get_share_url_template() {
		return $this->share_url_template;
	}

	/**
	 * Genereert link op te delen
	 *
	 * @param string $url
	 * @param string $title
	 * @return string
	 */
	public function generate_share_link( string $url, string $title ) {

		$template = $this->get_share_url_template();
		$url = urlencode( $url );
		$title = rawurlencode( html_entity_decode( $title ) );

		$vars = [
			'title'  => $title,
			'url'    => $url,
		];
	
		return SIW_Formatting::parse_template( $template, $vars );
	}
}
