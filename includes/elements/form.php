<?php declare(strict_types=1);

namespace SIW\Elements;

class Form extends Element {

	protected string $form_id;
	protected bool $single_column = false;
	protected \RW_Meta_Box $meta_box;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
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

	public function set_form_id( string $form_id ): self {
		$this->form_id = $form_id;
		$this->meta_box = siw_get_meta_box( "siw_form_{$form_id}" ) ?? new \RW_Meta_Box( [] );

		// Zet dummy id zodat er nooit per ongeluk gegevens getoond worden
		$this->meta_box->set_object_id( -1 );
		return $this;
	}

	public function set_field_value( string $field, mixed $value ): self {
		$field_index = array_search( $field, array_column( $this->meta_box->meta_box['fields'], 'id' ), true );
		if ( false === $field_index ) {
			return $this;
		}
		$this->meta_box->meta_box['fields'][ $field_index ]['std'] = $value;
		return $this;
	}

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

	public function set_single_column( bool $single_column ): self {
		$this->single_column = $single_column;
		return $this;
	}

	/** {@inheritDoc} */
	public function enqueue_scripts() {
		$this->meta_box->enqueue();
		self::enqueue_class_script( [ 'jquery', 'wp-api-request' ] );
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
