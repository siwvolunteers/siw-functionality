<?php declare(strict_types=1);

namespace SIW;

/**
 * Autoloader voor SIW classes
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Autoloader {

	/** Init */
	public function __construct( protected string $root_namespace, protected string $root_directory ) {
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/** Autoloader */
	public function autoload( string $class_name ) {

		/* Afbreken als het geen relevante class is */
		if ( ! str_starts_with( $class_name, $this->root_namespace ) ) {
			return;
		}

		// Root-namespace verwijderen
		$class_name = str_replace( $this->root_namespace . '\\', '', $class_name );

		// Bestandsnaam opbouwen
		$path = str_replace( '\\', '/', $class_name );
		$file = strtolower( str_replace( '_', '-', $path ) ) . '.php';
		$file = trailingslashit( $this->root_directory ) . $file;

		if ( file_exists( $file ) ) {
			require_once $file;
			return;
		}
	}
}
