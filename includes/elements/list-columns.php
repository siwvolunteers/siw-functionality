<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een lijst met kolommen te genereren
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class List_Columns extends Repeater {
	
	/** Aantal kolommen */
	protected int $columns = 1;

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'list';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'items'   => $this->items,
			'columns' => $this->columns,
		];
	}

	/** Zet aantal kolommen */
	public function set_columns( int $columns ) {
		$this->columns = $columns;
		return $this;
	}
}