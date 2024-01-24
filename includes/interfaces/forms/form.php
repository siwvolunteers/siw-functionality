<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

interface Form {
	public const FULL_WIDTH = 12;
	public const HALF_WIDTH = 6;
	public const THIRD_WIDTH = 4;
	public const QUARTER_WIDTH = 3;

	public function get_form_id(): string;

	public function get_form_name(): string;

	public function get_form_fields(): array;
}
