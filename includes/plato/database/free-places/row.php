<?php declare(strict_types=1);

namespace SIW\Plato\Database\Free_Places;

class Row extends \BerlinDB\Database\Row {

	readonly public int $id;
	readonly public string $project_id;
	readonly public string $code;
	readonly public string $start_date;
	readonly public string $end_date;
	readonly public int $numvol;
	readonly public int $free_m;
	readonly public int $free_f;
	readonly public int $free_teen;
	readonly public int $reserved;
	readonly public string $no_more_from;
	readonly public string $remarks;
	readonly public string $last_update;
	readonly public string $file_identifier_infosheet;

	public function __construct( $item ) {
		$this->id = (int) $item->id;
		$this->project_id = (string) $item->project_id;
		$this->code = (string) $item->code;
		$this->start_date = (string) $item->start_date;
		$this->end_date = (string) $item->end_date;
		$this->numvol = (int) $item->numvol;
		$this->free_m = (int) $item->free_m;
		$this->free_f = (int) $item->free_f;
		$this->free_teen = (int) $item->free_teen;
		$this->reserved = (int) $item->reserved;
		$this->no_more_from = (string) $item->no_more_from;
		$this->remarks = (string) $item->remarks;
		$this->last_update = (string) $item->last_update;
		$this->file_identifier_infosheet = (string) $item->file_identifier_infosheet;
	}
}
