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
		$item->id = (int) $item->id;
		$item->project_id = (string) $item->project_id;
		$item->code = (string) $item->code;
		$item->start_date = (string) $item->start_date;
		$item->end_date = (string) $item->end_date;
		$item->numvol = (int) $item->numvol;
		$item->free_m = (int) $item->free_m;
		$item->free_f = (int) $item->free_f;
		$item->free_teen = (int) $item->free_teen;
		$item->reserve = (int) $item->reserved;
		$item->no_more_from = (string) $item->no_more_from;
		$item->remarks = (string) $item->remarks;
		$item->last_update = (string) $item->last_update;
		$item->file_identifier_infosheet = (string) $item->file_identifier_infosheet;

		parent::__construct( $item );
	}
}
