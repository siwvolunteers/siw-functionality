<?php declare(strict_types=1);

namespace SIW\Elements;

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

	public function set_columns( int $columns ): self {
		$this->columns = $columns;
		return $this;
	}

	public function set_list_style_type( List_Style_Type $list_style_type ): self {
		$this->list_style_type = $list_style_type;
		return $this;
	}

	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
