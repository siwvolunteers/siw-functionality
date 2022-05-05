<?php declare(strict_types=1);

namespace SIW;

/**
 * Autoloader voor SIW classes
 *
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 */
class Autoloader {

	/** Root-namespace */
	protected string $root_namespace;

	/** Basisdirectory */
	protected string $root_directory;

	/** Init */
	public function __construct( string $root_namespace, string $root_directory ) {
		$this->root_namespace = $root_namespace;
		$this->root_directory = $root_directory;
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/** Autoloader */
	public function autoload( string $class ) {

		/* Afbreken als het geen relevante class is */
		if ( str_starts_with( $class, $this->root_namespace ) ) {
			return;
		}

		// Root-namespace verwijderen
		$class = str_replace( $this->root_namespace . '\\', '', $class );

		// Bestandsnaam opbouwen
		$path = str_replace( '\\', '/', $class );

		$file = trailingslashit( $this->root_directory ) . $path . '.php';
		$file = strtolower( str_replace( '_', '-', $file ) );
		if ( file_exists( $file ) ) {
			require_once $file;
			return;
		}
	}
}
