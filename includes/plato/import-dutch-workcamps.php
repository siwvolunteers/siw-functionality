<?php declare(strict_types=1);

namespace SIW\Plato;

class Import_Dutch_Workcamps extends Import_Workcamps {

	#[\Override]
	protected function get_endpoint(): string {
		return 'GetPartnerProjects';
	}

	protected bool $dutch_project = true;

	public function __construct() {
		parent::__construct();
		$this->add_query_arg_partner_webkey();
	}

	protected function add_query_arg_partner_webkey() {
		$this->add_query_arg( 'partnerOrganizationTechnicalKey', $this->webkey );
	}
}
