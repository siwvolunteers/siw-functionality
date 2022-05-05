<?php declare(strict_types=1);

namespace SIW\Interfaces\Elements;

/**
 * Interface voor een Mapplic kaart
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Interactive_Map {

	/** Geeft id van kaart terug */
	public function get_id() : string;

	/** Geeft bestandsnaam van kaart terug (zonder extensie) */
	public function get_file() : string;

	/** Geeft opties van kaart terug */
	public function get_options() : array;

	/** Geeft gegevens (bijv. afmetingen) van kaart terug */
	public function get_map_data() : array;

	/** Geeft categorieën van kaart terug */
	public function get_categories() : array;

	/** Geef locaties van kaart terug */
	public function get_locations() : array;

	/** Geeft alternatieve content voor mobiel terug */
	public function get_mobile_content() : string;
}
