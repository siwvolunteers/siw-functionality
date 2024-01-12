<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een lijst met kolommen te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class List_Columns extends Repeater {

	protected int $columns = 1;

	protected List_Style_Type $list_style_type = List_Style_Type::DISC;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'items'           => $this->items,
			'columns'         => $this->columns,
			'list_style_type' => $this->list_style_type->value,
		];
	}

	/** Zet aantal kolommen */
	public function set_columns( int $columns ): self {
		$this->columns = $columns;
		return $this;
	}

	public function set_list_style_type( List_Style_Type $list_style_type ): self {
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
