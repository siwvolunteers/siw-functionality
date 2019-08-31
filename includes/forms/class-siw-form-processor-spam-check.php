<?php

/**
 * Class voor Anti-Spam formprocessor
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_External_Spam_Check
 * */
class SIW_Form_Processor_Spam_Check {

	/**
	 * Init
	 */
	public function __construct() {
		add_filter( 'caldera_forms_get_form_processors', [ $this, 'add_form_processor'] );

		if ( true === $this->report_only() ) {
			add_filter( 'caldera_forms_submit_get_form', [ $this, 'set_subject'], PHP_INT_MAX );
		}
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
			'pre_processor' => $this->report_only() ? null : [ $this, 'preprocess'],
			'processor'     => $this->report_only() ? [ $this, 'process'] : null,
			'single'        => true,
			'magic_tags'    => [
				'spam'
			]
		];
		return $processors;
	}

	/**
	 * Geeft resultaat spamcheck voor emailsubject terug
	 *
	 * @param array $config
	 * @param array $form
	 * @param string $process_id
	 */
	public function process( array $config, array $form, string $process_id ) {
		return [
			'spam' => $this->spam_check( $config, $form ) ? '[Spam alert]' : '',
		];
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
		if ( true === $this->spam_check( $config, $form ) ) {
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
	 * @todo wp_blacklist_check() gebruiken voor inhoud van bericht
	 */
	protected function spam_check( array $config, array $form ) {
		$data = Caldera_Forms::get_submission_data( $form );

		$spam_check = new SIW_External_Spam_Check();
		$spam_check->set_email( $data[ $config['email'] ] );
		$spam_check->set_ip( $_SERVER['REMOTE_ADDR'] );
		
		return $spam_check->is_spammer();
	}

	/**
	 * Geeft aan of spam alleen gerapporteert wordt en niet geblokkeerd
	 * 
	 * @return bool
	 */
	protected function report_only() {
		return ( 'report' === siw_get_option( 'spam_check_mode' ) );
	}

	/**
	 * Voegt resultaat spamcheck aan subject toe
	 *
	 * @param array $form
	 * @param array
	 */
	public function set_subject( array $form ) {
		$form['mailer']['email_subject'] = '{siw_spam_check:spam}' . $form['mailer']['email_subject'];
		return $form;
	}

}