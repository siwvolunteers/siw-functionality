<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Elements\Unordered_List\List_Style_Position;
use SIW\Data\Elements\Unordered_List\List_Style_Type;

class Unordered_List extends Repeater {

	protected int $columns = 1;
	protected List_Style_Type $list_style_type = List_Style_Type::DISC;
	protected List_Style_Position $list_style_position = List_Style_Position::INSIDE;

	#[\Override]
	protected function get_template_variables(): array {
		return [
			'items'               => $this->items,
			'columns'             => $this->columns,
			'list_style_type'     => $this->list_style_type->value,
			'list_style_position' => $this->list_style_position->value,
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

	public function set_list_style_position( List_Style_Position $list_style_position ): self {
		$this->list_style_position = $list_style_position;
		return $this;
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
