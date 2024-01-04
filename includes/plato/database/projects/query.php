<?php declare(strict_types=1);

namespace SIW\Plato\Database\Projects;

class Query extends \BerlinDB\Database\Query {

	/** {@inheritDoc} */
	protected $table_name = 'plato_projects';

	/** {@inheritDoc} */
	protected $table_alias = 'pla_pro';

	/** {@inheritDoc} */
	protected $table_schema = Schema::class;

	/** {@inheritDoc} */
	protected $item_name = 'project';

	/** {@inheritDoc} */
	protected $item_name_plural = 'projects';

	/** {@inheritDoc} */
	protected $item_shape = Row::class;
}
