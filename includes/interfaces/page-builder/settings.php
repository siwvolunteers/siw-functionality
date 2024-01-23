<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

interface Settings {

	public function add_settings( array $fields ): array;

	public function set_settings_defaults( array $defaults ): array;
}
