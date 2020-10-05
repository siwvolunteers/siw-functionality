<?php declare(strict_types=1);

namespace SIW;

/**
 * Autoloader voor SIW classes
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Autoloader {

	/**
	 * Basis-namespace
	 */
	protected string $base_namespace = 'SIW';

	/**
	 * Basisdirectory
	 */
	protected string $base_dir;

	/**
	 * Registreert autoloader
	 */
	public function register() {
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/**
	 * Zet base directory
	 *
	 * @param string $base_dir
	 */
	public function set_base_dir( string $base_dir ) {
		$this->base_dir = trailingslashit( $base_dir );
	}

	/**
	 * Autoloader
	 *
	 * @param string $class
	 */
	public function autoload( string $class ) {

		/* Afbreken als het geen SIW class is */
		$path = explode( '\\', $class );
		if ( ! is_array( $path ) || $this->base_namespace !== $path[0] ) {
			return;
		}

		//Basis-namespace verwijderen
		unset( $path[0]);

		//Bestandsnaam opbouwen
		$file = $this->base_dir . implode( '/', $path ) . '.php';
		$file = strtolower( str_replace( '_', '-', $file ) );
		if ( file_exists( $file ) ) {
			require_once $file;
			return;
		}
	}
}
