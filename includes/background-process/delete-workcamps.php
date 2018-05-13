<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SIW_Delete_Workcamps_Process extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'delete_workcamps_process';


    /**
     * Verwijderen alle producten (inclusief variaties)
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

		$product = wc_get_product( $item );
		if ( false == $product ) {
			return false;
		}
		$variations = $product->get_children();
		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( false == $variation ) {
				continue;
			}
			$variation->delete( true );
		}
		$product->delete( true );

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		parent::complete();
		siw_debug_log( 'Eind verwijderen projecten' );
	}
}

$GLOBALS['siw_delete_workcamps_process'] = new SIW_Delete_Workcamps_Process();
