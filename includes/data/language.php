<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een taal
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Language {

	/**
	 * Slug
	 */
	protected string $slug;
	
	/**
	 * Naam
	 */
	protected string $name;

	/**
	 * PLATO-code
	 */
	protected string $plato_code;

	/**
	 * Geeft dit een taal is die een vrijwilliger kan opgeven
	 */
	protected bool $volunteer_language;

	/**
	 * Geeft aan of dit een projecttaal kan zijn
	 */
	protected bool $project_language;

	/**
	 * @param array $language
	 */
	public function __construct( array $language ) {
		$defaults = [
			'slug'               => '',
			'name'               => '',
			'plato'              => '',
			'volunteer_language' => false,
			'project_language'   => false,
		];
		$language = wp_parse_args( $language, $defaults );
		$this->slug = $language['slug'];
		$this->name = $language['name'];
		$this->plato_code = $language[ 'plato'];
		$this->volunteer_language = $language['volunteer_language']; 
		$this->project_language = $language['project_language']; 
	}

	/**
	 * Geeft slug van taal terug
	 * 
	 * @return string
	 */
	public function get_slug() : string {
		return $this->slug;
	}

	/**
	 * Geeft naam van taal terug
	 * 
	 * @return string
	 */
	public function get_name() : string {
		return $this->name;
	}

	/**
	 * Geeft PLATO-code van taal terug
	 * 
	 * @return string
	 */
	public function get_plato_code() : string {
		return $this->plato_code;
	}

	/**
	 * Geeft terug of dit een taal is die een vrijwilliger kan opgeven
	 *
	 * @return bool
	 */
	public function is_volunteer_language() : bool {
		return $this->volunteer_language;
	}

	/**
	 * Geeft terug of dit een projecttaal kan zijn
	 *
	 * @return bool
	 */
	public function is_project_language() : bool {
		return $this->project_language;
	}
}
