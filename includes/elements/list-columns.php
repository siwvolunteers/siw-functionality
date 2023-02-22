<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een lijst met kolommen te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class List_Columns extends Repeater {

	const ASSETS_HANDLE = 'siw-list';

	/** Aantal kolommen */
	protected int $columns = 1;

	protected string $list_style_type = '';

	protected string $marker_color = '';

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'list';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'items'           => $this->items,
			'columns'         => $this->columns,
			'list_style_type' => $this->list_style_type,
			'marker_color'    => $this->marker_color,
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

	public function set_marker_color( string $marker_color ): self {
		$this->marker_color = $marker_color;
		return $this;
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/list.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/list.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
