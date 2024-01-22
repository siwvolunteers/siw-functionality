<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Integrations\Mailjet;
use SIW\Jobs\Update_Job;

class Update_Mailjet_Properties extends Update_Job {

	private const ACTION_HOOK = self::class;

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Bijwerken Mailjet eigenschappen', 'siw' );
	}

	public function start(): void {
		$existing_properties = Mailjet::create()->retrieve_properties( 'name' );
		$missing_properties = array_diff_key( Mailjet::PROPERTIES, $existing_properties );
		$this->enqueue_items( array_keys( $missing_properties ), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function create_property( string $property ) {
		$datatype = Mailjet::PROPERTIES[ $property ];
		Mailjet::create()->create_property( $property, $datatype );
	}
}
