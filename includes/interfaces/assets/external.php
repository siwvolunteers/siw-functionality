<?php declare(strict_types=1);

namespace SIW\Interfaces\Assets;

/**
 * Interface voor externe assets (t.b.v. DNS-prefetch en uitsluiten van optimalisatie)
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface External {
	/** Geeft externe domein terug */
	public function get_external_domain(): string;
}