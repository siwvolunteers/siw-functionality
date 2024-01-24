<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

interface Extension {

	public function supports_widgets(): bool;

	public function supports_cells(): bool;

	public function supports_rows(): bool;
}
