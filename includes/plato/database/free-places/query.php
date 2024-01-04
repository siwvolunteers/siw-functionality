<?php declare(strict_types=1);

namespace SIW\Plato\Database\Free_Places;

class Query extends \BerlinDB\Database\Query {

	protected $table_name = 'plato_free_places';

	protected $table_alias = 'pl_fr_pl';

	protected $table_schema = Schema::class;

	protected $item_name = 'free_places';

	protected $item_name_plural = 'free_places';

	protected $item_shape = Row::class;
}
