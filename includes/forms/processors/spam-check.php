<?php declare(strict_types=1);

namespace SIW\Forms\Processors;

use SIW\Interfaces\Forms\Processor as Processor_Interface;

use SIW\External\Spam_Check as External_Spam_Check;

/**
 * Class voor Anti-Spam formprocessor
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Spam_Check implements Processor_Interface {

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
	public function preprocess( array $config, array $form, string $process_id ) : ?array {
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
		$spam_check = new External_Spam_Check();
		$spam_check->set_email( $data[ $config['email'] ] );
		$spam_check->set_ip( $_SERVER['REMOTE_ADDR'] );
		
		return $spam_check->is_spammer();
	}
}
