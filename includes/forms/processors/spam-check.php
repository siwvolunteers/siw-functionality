<?php declare(strict_types=1);

namespace SIW\Forms\Processors;

use SIW\Interfaces\Forms\Pre_Processor as Pre_Processor_Interface;

use SIW\Helpers\Spam_Check as Spam_Check_Helper;
use SIW\Util\Logger;

/**
 * Class voor Anti-Spam formprocessor
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Spam_Check implements Pre_Processor_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'spam_check';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Spam check', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_description(): string {
		return __( 'Spam check bij StopForumSpam.com', 'siw' );
	}

	/** Breek formulierverwerking af als het een spammer is */
	public function pre_process( array $config, array $form, string $process_id ) : ?array {
		if ( $this->is_spam( $config, $form ) ) {
			return [
				'note' => __( 'Er is helaas iets misgegaan.', 'siw' ),
				'type' => 'error'
			];
		}
		return null;
	}

	/**
	 * Voer spamcheck uit
	 * @todo wp_blacklist_check() gebruiken voor inhoud van bericht / of setting met blacklist van woorden maken
	 */
	protected function is_spam( array $config, array $form ) : bool {
		$data = \Caldera_Forms::get_submission_data( $form );

		//FIXME:: tijdelijke check om spam te voorkomen: bots vullen bij voor- en achternaam hetzelfde in
		$first_name = $data['voornaam'] ?? null;
		$last_name = $data['achternaam'] ?? null;
		
		if ( null != $first_name && null != $last_name && $first_name == $last_name ) {
			Logger::info( "Gefilterd als spam: voornaam gelijk aan achternaam", 'spam-check-processor' );
			return true;
		}

		return Spam_Check_Helper::create()
			->set_email( $data[ $config['email'] ] )
			->set_ip( $_SERVER['REMOTE_ADDR'] )
			->is_spam();
	}
}
