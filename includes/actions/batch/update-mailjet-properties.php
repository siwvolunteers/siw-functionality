<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Integrations\Mailjet;
use SIW\Integrations\Mailjet\Property;
use SIW\Interfaces\Actions\Batch as I_Batch_Action;

/**
 *
 * TODO:
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Update_Mailjet_Properties implements I_Batch_Action {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'update_mailjet_properties';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Bijwerken Mailjet eigenschappen', 'siw' );
	}

	/** {@inheritDoc} */
	public function select_data(): array {
		$existing_properties = Mailjet::create()->retrieve_properties( 'name' );
		$properties = array_column( Property::cases(), 'value' );
		$missing_properties = array_diff_key( $properties, $existing_properties );
		return array_keys( $missing_properties );
	}

	/** {@inheritDoc} */
	public function process( $value ) {
		$property = Property::from( $value );
		Mailjet::create()->create_property( $property->value, $property->get_data_type() );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return true;
	}
}
