<?php declare(strict_types=1);

namespace SIW\Actions\Async;

use SIW\Interfaces\Actions\Async as Async_Action_Interface;

/**
 * Async actie voor het exporteren van een aanmelding naar Plato
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Export_Plato_Application implements Async_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'export_application_to_plato';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Exporteer aanmelding naar Plato', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_argument_count(): int {
		return 1;
	}

	/** {@inheritDoc} */
	public function process() {
		//TODO:
	}

}