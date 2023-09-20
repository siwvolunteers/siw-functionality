<?php declare(strict_types=1);

namespace SIW\Traits;

/** Geneert assets handle obv class */
trait Assets_Handle {
	public static function get_assets_handle(): string {
		return strtolower( str_replace( [ '\\', '_' ], '-', static::class ) );
	}
}
