<?php declare(strict_types=1);

namespace SIW\Interfaces\Blocks;

/**
 * Interface voor definiëren van een MB block
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Block {


	/** Geeft ID van Block terug */
	public function get_id() : string;

	/** Geeft naam van block terug */
	public function get_name() : string;
	
	/** Geeft velden van block terug */
	public function get_fields() : array;

    /** Geeft naam van moustache template terug */
	public function get_template() : string;
	
	/** Geeft  moustache template varioabelen terug*/
	public function get_template_vars($attributes) : array;

}