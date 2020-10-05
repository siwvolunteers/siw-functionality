<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Formatting;

/**
 * Bevat informatie over een sociaal netwerk
 * 
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @since       3.0.0
 */
class Social_Network {

	/**
	 * Slug van het netwerk
	 */
	protected string $slug;

	/**
	 * Naam van het netwerk
	 */
	protected string $name;

	/**
	 * CSS-class van icoon
	 */
	protected string $icon_class;

	/**
	 * Kleurcode
	 */
	protected string $color;

	/**
	 * URL van netwerk om te volgen
	 */
	protected ?string $follow_url;

	/**
	 * URL-template voor delen
	 */
	protected ?string $share_url_template;

	/**
	 * Is netwerk om te delen?
	 */
	protected bool $share;

	/**
	 * Is netwerk om te volgen?
	 */
	protected bool $follow;

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
			'color'              => null,
			'follow'             => false,
			'follow_url'         => null,
			'share'              => false,
			'share_url_template' => null
		];
		$network = wp_parse_args( $network, $defaults );

		$this->slug = $network['slug'];
		$this->name = $network['name'];
		$this->icon_class = $network['icon_class'];
		$this->color = $network['color'];
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
	public function get_slug() : string {
		return $this->slug;
	}

	/**
	 * Geeft de naam van het netwerk terug
	 * 
	 * @return string
	 */
	public function get_name() : string {
		return $this->name;
	}

	/**
	 * Geeft icon class voor voor netwerk terug
	 * 
	 * @return string
	 */
	public function get_icon_class() : string {
		return $this->icon_class;
	}

	/**
	 * Geeft kleurcode van netwerk terug
	 * 
	 * @return string
	 */
	public function get_color() : string {
		return $this->color;
	}

	/**
	 * Geeft aan of via dit netwerk gedeeld kan worden
	 *
	 * @return bool
	 */
	public function is_for_sharing() : bool {
		return $this->share;
	}

	/**
	 * Geeft aan of dit netwerk gevolgd kan worden
	 *
	 * @return bool
	 */
	public function is_for_following() : bool {
		return $this->follow;
	}

	/**
	 * Geeft URL van network om te volgen terug
	 *
	 * @return string
	 */
	public function get_follow_url() : string {
		return $this->follow_url;
	}

	/**
	 * Geeft template voor url om te delen terug
	 *
	 * @return string
	 */
	public function get_share_url_template() : string {
		return $this->share_url_template;
	}

	/**
	 * Genereert link op te delen
	 *
	 * @param string $url
	 * @param string $title
	 * @return string
	 */
	public function generate_share_link( string $url, string $title ) :string {

		$template = $this->get_share_url_template();
		$url = urlencode( $url );
		$title = rawurlencode( html_entity_decode( $title ) );

		$vars = [
			'title'  => $title,
			'url'    => $url,
		];
	
		return Formatting::parse_template( $template, $vars );
	}
}
