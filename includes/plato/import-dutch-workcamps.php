<?php declare(strict_types=1);

namespace SIW\Plato;

/**
 * Importeer Nederlandse Groepsprojecten uit Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Import_Dutch_Workcamps extends Import_Workcamps {

	/** {@inheritDoc} */
	protected string $endpoint = 'GetPartnerProjects';

	/** {@inheritDoc} */
	protected string $name = 'importeren Nederlandse groepsprojecten';

	/** Constructor */
	public function __construct() {
		parent::__construct();
		$this->add_query_arg_partner_webkey();
	}

	/** Voeg de Plato-webkey toe als query arg */
	protected function add_query_arg_partner_webkey() {
		$this->add_query_arg( 'partnerOrganizationTechnicalKey', $this->webkey );
	}
}
