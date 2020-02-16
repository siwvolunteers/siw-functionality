<?php

namespace SIW;

use SIW\Forms\Form;
use SIW\Forms\Processor_Spam_Check;

/**
 * Formulieren
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Forms {

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
			new Form( $form_id );
		}
	}

	/**
	 * Voegt formprocessors toe
	 */
	public function register_form_processors() {
		new Processor_Spam_Check(); 
	}
}
