<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met settings
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
interface Settings {

	/**
	 * Voegt instelling voor toe aan PB-settings
	 */
	public function add_settings( array $fields ) : array;

	/**
	 * Zet standaardwaarden voor PB-settings
	 */
	public function set_settings_defaults( array $defaults ) : array;

}