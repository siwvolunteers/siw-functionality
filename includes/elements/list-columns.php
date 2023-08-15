<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een lijst met kolommen te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class List_Columns extends Repeater {

	// TODO: php 8.1 enum
	const LIST_STYLE_TYPE_NONE = 'none';
	const LIST_STYLE_TYPE_DISC = 'disc';
	const LIST_STYLE_TYPE_CIRCLE = 'circle';
	const LIST_STYLE_TYPE_SQUARE = 'square';
	const LIST_STYLE_TYPE_CHECK = 'check';

	/** Aantal kolommen */
	protected int $columns = 1;

	protected string $list_style_type = self::LIST_STYLE_TYPE_DISC;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'items'           => $this->items,
			'columns'         => $this->columns,
			'list_style_type' => $this->list_style_type,
		];
	}

	/** Zet aantal kolommen */
	public function set_columns( int $columns ): self {
		$this->columns = $columns;
		return $this;
	}

	public function set_list_style_type( string $list_style_type ): self {
		$this->list_style_type = $list_style_type;
		return $this;
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/elements/list.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/elements/list.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}
}
