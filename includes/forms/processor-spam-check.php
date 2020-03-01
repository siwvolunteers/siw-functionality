<?php

namespace SIW\Forms;

use SIW\External\Spam_Check as External_Spam_Check;

/**
 * Class voor Anti-Spam formprocessor
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Processor_Spam_Check {

	/**
	 * Init
	 */
	public function __construct() {
		add_filter( 'caldera_forms_get_form_processors', [ $this, 'add_form_processor'] );
	}

	/**
	 * Voegt processer toe
	 *
	 * @param array $processors
	 * @return array
	 */
	public function add_form_processor( array $processors ) {
		$processors['siw_spam_check'] = [
			'name'          => __( 'Spam Check', 'siw' ),
			'description'   => __( 'Spam check bij StopForumSpam.com', 'siw' ),
			'pre_processor' => [ $this, 'preprocess'],
			'single'        => true,
			'magic_tags'    => [
				'spam'
			]
		];
		return $processors;
	}

	/**
	 * Breek formulierverwerking af als het een spammer is
	 *
	 * @param array $config
	 * @param array $form
	 * @param string $process_id
	 * 
	 * @return array
	 */
	public function preprocess( array $config, array $form, string $process_id ) {
		if ( $this->is_spam( $config, $form ) ) {
			return [
				'note' => __( 'Er is helaas iets misgegaan.', 'siw' ),
				'type' => 'error'
			];
		}
		return;
	}

	/**
	 * Voer spamcheck uit
	 *
	 * @param array $config
	 * @param array $form
	 * @return bool
	 * 
	 * @todo wp_blacklist_check() gebruiken voor inhoud van bericht / of setting met blacklist van woorden maken
	 */
	protected function is_spam( array $config, array $form ) {

		$data = \Caldera_Forms::get_submission_data( $form );
		$spam_check = new External_Spam_Check();
		$spam_check->set_email( $data[ $config['email'] ] );
		$spam_check->set_ip( $_SERVER['REMOTE_ADDR'] );
		
		return $spam_check->is_spammer();
	}
}
