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

	/** Slug van het netwerk */
	protected string $slug;

	/** Naam van het netwerk */
	protected string $name;

	/** CSS-class van icoon */
	protected string $icon_class;

	/** Kleurcode */
	protected string $color;

	/** URL van netwerk om te volgen */
	protected ?string $follow_url;

	/** URL-template voor delen */
	protected ?string $share_url_template;

	/** Is netwerk om te delen? */
	protected bool $share;

	/** Is netwerk om te volgen? */
	protected bool $follow;

	/** Constructor */
	public function __construct( array $data ) {
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
		$data = wp_parse_args( $data, $defaults );
		$data = wp_array_slice_assoc( $data, array_keys( $defaults ) );
		
		foreach( $data as $key => $value ) {
			$this->$key = $value;
		}
	}

	/** Geeft slug van netwerk terug */
	public function get_slug() : string {
		return $this->slug;
	}

	/** Geeft de naam van het netwerk terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft icon class voor voor netwerk terug */
	public function get_icon_class() : string {
		return $this->icon_class;
	}

	/** Geeft kleurcode van netwerk terug */
	public function get_color() : string {
		return $this->color;
	}

	/** Geeft aan of via dit netwerk gedeeld kan worden */
	public function is_for_sharing() : bool {
		return $this->share;
	}

	/** Geeft aan of dit netwerk gevolgd kan worden */
	public function is_for_following() : bool {
		return $this->follow;
	}

	/** Geeft URL van network om te volgen terug */
	public function get_follow_url() : string {
		return $this->follow_url;
	}

	/** Geeft template voor url om te delen terug */
	public function get_share_url_template() : string {
		return $this->share_url_template;
	}
}
