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
		$self->register_forms();
	}

	/**
	 * Registreert alle formulieren
	 */
	protected function register_forms() {
		foreach ( $this->forms as $form_id ) {
			new SIW_Form( $form_id );
		}
	}

	protected function register_form_processors() {
		
	}
}