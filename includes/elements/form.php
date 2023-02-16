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
		// Meta box genereren
		ob_start();
		$this->meta_box->show();
		$meta_box = ob_get_clean();

		$form_handle = str_replace( '_', '-', $this->form_id );
		return [
			'form_id'       => $this->form_id,
			'meta_box'      => $meta_box,
			'api_path'      => "siw/v1/form/{$form_handle}",
			'single_column' => $this->single_column,
		];
	}

	/** Zet id van het formulier */
	public function set_form_id( string $form_id ): self {
		$this->form_id = $form_id;
		$this->meta_box = siw_get_meta_box( "siw_form_{$form_id}" ) ?? new \RW_Meta_Box( [] );

		// Zet dummy id zodat er nooit per ongeluk gegevens getoond worden
		$this->meta_box->set_object_id( -1 );
		return $this;
	}

	/** Zet veld op bepaalde waarde */
	public function set_field_value( string $field, mixed $value ): self {

		$field_index = array_search( $field, array_column( $this->meta_box->meta_box['fields'], 'id' ), true );
		if ( false === $field_index ) {
			return $this;
		}
		$this->meta_box->meta_box['fields'][ $field_index ]['std'] = $value;
		return $this;
	}

	/** Verberg label en voeg eventueel placeholder toe */
	public function hide_labels(): self {
		$placeholder_fields = [ 'text', 'search', 'url', 'tel', 'email', 'password', 'number' ];

		foreach ( $this->meta_box->meta_box['fields'] as $index => $field ) {
			if ( in_array( $field['type'], $placeholder_fields, true ) ) {
				$field['placeholder'] = $field['name'];
			}
			$field['attributes']['aria-label'] = $field['name'];
			$field['name'] = '';
			$this->meta_box->meta_box['fields'][ $index ] = $field;
		}
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
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/elements/form.js', [ 'jquery', 'wp-api-request' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/form.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/form.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

}
