<?php declare(strict_types=1);

namespace SIW\Plato\Database\Partners;

class Query extends \BerlinDB\Database\Query {

	protected $table_name = 'plato_partners';

	protected $table_alias = 'pla_par';

	protected $table_schema = Schema::class;

	protected $item_name = 'partner';

	protected $item_name_plural = 'partners';

	protected $item_shape = Row::class;
}
