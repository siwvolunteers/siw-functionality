<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Bevat informatie over een sociaal netwerk
 * 
 * @package     SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Social_Network {

	/**
	 * @var string
	 */
	protected $slug;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected $follow_url;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected $share_url;

	/**
	 * Undocumented variable
	 *
	 * @var bool
	 */
	protected $for_sharing;

	/**
	 * Undocumented variable
	 *
	 * @var bool
	 */
	protected $for_following;



	public function __construct( $network ) {
		$defaults = [
			'slug'          => '',
			'name'          => '',
			'follow'        => false,
			'follow_url'    => null,
			'share'         => false,
			'share_url'     => null
		];
		$network = wp_parse_args( $network, $defaults );

		$this->set_slug( $network['slug'] );
		$this->set_name( $network['name'] );
		$this->set_for_following( $network['follow'] );
		$this->set_follow_url( $network['follow_url'] );
		$this->set_for_sharing( $network['share'] );
		$this->set_share_url( $network['share_url'] );
	}

	/**
	 * @param string $slug
	 * @return $this
	 */
	protected function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	protected function set_name( $name ) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Undocumented function
	 *
	 * @param bool $for_sharing
	 * @return $this
	 */
	protected function set_for_sharing( $for_sharing ) {
		$this->for_sharing = $for_sharing;
		return $this;
	}

	/**
	 * Undocumented function
	 *
	 * @return boolean
	 */
	public function is_for_sharing() {
		return $this->for_sharing;
	}

	/**
	 * Undocumented function
	 *
	 * @param bool $for_following
	 * @return $this
	 */
	protected function set_for_following( $for_following ) {
		$this->for_following = $for_following;
		return $this;
	}

	/**
	 * Undocumented function
	 *
	 * @return boolean
	 */
	public function is_for_follwing() {
		return $this->for_following;
	}

	/**
	 * Undocumented function
	 *
	 * @param string $follow_url
	 * @return void
	 */
	protected function set_follow_url( $follow_url ) {
		$this->follow_url = $follow_url;
		return $this;
	}

	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function get_follow_url() {
		return $this->follow_url;
	}

	/**
	 * Undocumented function
	 *
	 * @param string $template
	 * @return $this
	 */
	protected function set_share_url( $share_url ) {
		$this->share_url = $share_url;
		return $this;
	}

	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function get_share_url() {
		return $this->share_url;
	}

	/**
	 * Undocumented function
	 *
	 * @param string $url
	 * @param string $title
	 * @return void
	 */
	public function generate_share_link( $url, $title ) {

		$template = $this->get_share_url();
		$url = urlencode( $url );
		$title = rawurlencode( html_entity_decode( $title ) );

		$vars = [
			'title'  => $title,
			'url'    => $url,
		];
	
		return SIW_Formatting::render_template( $template, $vars );
	}

}