<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een formulier te genereren (o.b.v. een MetaBox)
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Form extends Element {

	/** Handle voor script */
	const ASSETS_HANDLE = 'siw-forms';

	/** ID van formulier */
	protected string $form_id;

	/** Moet formulier in 1 kolom getoond worden? */
	protected bool $single_column = false;

	/** MetaBox instantie */
	protected \RW_Meta_Box $meta_box;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'form';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		//Meta box genereren
		ob_start();
		$this->meta_box->show();
		$meta_box = ob_get_clean();

		$form_handle = str_replace( '_', '-', $this->form_id );
		return [
			'id'            => wp_unique_id( 'siw-form-' ),
			'form_id'       => $this->form_id,
			'meta_box'      => $meta_box,
			'api_path'      => "siw/v1/form/{$form_handle}",
			'single_column' => $this->single_column,
		];
	}

	/** Zet id van het formulier */
	public function set_form_id( string $form_id ): self {
		$this->form_id = $form_id;
		$this->meta_box = siw_get_meta_box( "siw_form_{$form_id}" ) ?? new \RW_Meta_Box([]);

		//Zet dummy id zodat er nooit per ongeluk gegevens getoond worden
		$this->meta_box->set_object_id(-1);
		return $this;
	}

	/** Zet of het formulier in 1 kolom getoond moet worden */
	public function set_single_column( bool $single_column ): self {
		$this->single_column = $single_column;
		return $this;
	}

	/** {@inheritDoc} */
	public function enqueue_scripts() {
		$this->meta_box->enqueue();
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/siw-forms.js', [ 'jquery', 'wp-api-request' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}
}
