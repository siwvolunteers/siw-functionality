<?php declare(strict_types=1);

namespace SIW\Plato;

/**
 * Importeer Nederlandse Groepsprojecten uit Plato
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Dutch_Workcamps extends Import_Workcamps {

	#[\Override]
	protected string $endpoint = 'GetPartnerProjects';

	/** Geef aan dat dit Nederlandse projecten zijn */
	protected bool $dutch_project = true;

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
