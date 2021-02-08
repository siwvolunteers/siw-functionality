<?php declare(strict_types=1);

namespace SIW\Actions;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;
use SIW\Interfaces\Actions\Action as Action_Interface;

/**
 * Loader voor actions
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'actions';
	}

	/** {@inheritDoc} */
	public function get_classes() : array {
		return [
			Delete_Applications::class,
			Send_Workcamp_Approval_Emails::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $action ) {
		if( ! is_a( $action, Action_Interface::class ) ) {
			return;
		}
		new Action( $action );
	}
}
