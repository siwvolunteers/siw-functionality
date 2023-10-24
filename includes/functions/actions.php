<?php declare(strict_types=1);

/** Zet async actie in de wachtrij */
function siw_enqueue_async_action( string $id, array $data ) {
	do_action( "siw_async_action_{$id}_enqueue", $data );
}
