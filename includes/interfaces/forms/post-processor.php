<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor Form Processor
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Post_Processor extends Form_Processor {

	/** Preprocess callback */
	public function post_process( array $config, array $form, string $process_id ) : ?array;
}
