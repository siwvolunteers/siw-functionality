<?php declare(strict_types=1);

namespace SIW\Interfaces\Options;

interface Option {

	/** Geeft ID van optiepagina terug */
	public function get_id(): string;

	/** Geeft naam van optiepagina terug */
	public function get_title(): string;

	/** Geeft tabs terug */
	public function get_tabs(): array;

	/** Geeft velden terug */
	public function get_fields(): array;

	/** Geeft benodigde capability terug */
	public function get_capability(): string;

	/** Geeft parent pagina terug */
	public function get_parent_page(): string;
}
