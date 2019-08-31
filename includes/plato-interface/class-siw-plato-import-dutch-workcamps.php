<?php

/**
 * Importeer Nederlandse Groepsprojecten uit Plato
 * 
 * @package   SIW\Plato
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Plato_Import_Dutch_Workcamps extends SIW_Plato_Import_Workcamps {

	/**
	 * {@inheritDoc}
	 */
	protected $endpoint = 'GetPartnerProjects';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'importeren Nederlandse groepsprojecten';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->add_query_arg_partner_webkey();
	}

	/**
	 * Voeg de Plato-webkey toe als query arg
	 */
	protected function add_query_arg_partner_webkey() {
		$this->add_query_arg( 'partnerOrganizationTechnicalKey', $this->webkey );
	}

}
