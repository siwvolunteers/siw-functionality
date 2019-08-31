<?php

/**
 * Formulieren
 *
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_Forms {

	/**
	 * Formulieren
	 *
	 * @var array
	 */
	protected $forms = [];

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		$self->forms = siw_get_data_file_ids( 'forms' );
		add_action( 'init', [ $self, 'register_forms' ] );
		add_action( 'init', [ $self, 'register_form_processors' ] );

	}

	/**
	 * Registreert alle formulieren
	 */
	public function register_forms() {
		foreach ( $this->forms as $form_id ) {
			new SIW_Form( $form_id );
		}
	}

	/**
	 * Voegt formprocessors toe
	 */
	public function register_form_processors() {
		new SIW_Form_Processor_Spam_Check(); 
	}
}
