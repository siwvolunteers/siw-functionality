<?php declare(strict_types=1);

namespace SIW\Core;

use SIW\Database_Table;
use SIW\Helpers\Database;

/**
 * Plugin update
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Update {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wppusher_plugin_was_updated', [ $self, 'schedule_plugin_update_hook' ] );
		add_filter( 'woocommerce_debug_tools', [ $self, 'add_database_update_to_wc_debug_tools'] );
		add_action( 'siw_update_plugin', 'flush_rewrite_rules' );
		add_action( 'siw_update_plugin', [ $self, 'maybe_update_database'] );
	}

	/** Zet taak klaar om pluginupdate te verwerken */
	public function schedule_plugin_update_hook() {
		wp_schedule_single_event( time(), 'siw_update_plugin' );
	}

	/** Voegt update-acties toe aan WooCommerce debug tools (om handmatig te starten) */
	public function add_database_update_to_wc_debug_tools( array $tools ) : array {
		$tools[ 'siw_update_database' ] = [
			'name'     => 'SIW: ' . __( 'Database updaten', 'siw' ),
			'button'   => __( 'Starten', 'siw' ),
			'desc'     => __( 'Voer update van database uit', 'siw' ),
			'callback' => [ $this, 'maybe_update_database'],
		];
		return $tools;
	}

	/**
	 * Voert eventueel database upgrade uit TODO: versienummer opslaan
	 */
	public function maybe_update_database() {

		$tables = [
			Database_Table::PLATO_PROJECTS(),
			Database_Table::PLATO_PROJECT_FREE_PLACES(),
			Database_Table::PLATO_PROJECT_IMAGES(),
		];

		//Tabellen toevoegen
		foreach ( $tables as $table ) {
			$db = new Database( $table );
			if ( ! $db->create_table() ) {
				//Afbreken als tabel aanmaken mislukt
				return false;
			};
		}

		//Foreign key toevoegen
		$db = new Database( Database_Table::PLATO_PROJECT_IMAGES() );
		if (! $db->add_foreign_key( Database_Table::PLATO_PROJECTS(), ['project_id'], ['project_id'] ) ) {
			return false;
		}
		return true;
	}
}
