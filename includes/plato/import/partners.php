<?php declare(strict_types=1);

namespace SIW\Plato\Import;

use SIW\Plato\Database\Partners\Query;
use SIW\Plato\Import;

class Partners extends Import {

	protected function get_endpoint(): string {
		return 'ListPartners';
	}

	protected function process_xml() {
		$query = new Query();
		foreach ( $this->xml_response->partner as $partner ) {
			$item = [
				'technical_key' => (string) $partner->technicalKey, //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'name'          => (string) $partner->name,
			];
			if ( $query->add_item( $item ) ) {
				$this->data[] = (string) $partner->technicalKey; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			}
		}
	}
}
