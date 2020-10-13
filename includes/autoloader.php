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
	 * Init
	 */
	public function __construct( string $base_namespace, string $base_dir ) {
		$this->base_namespace = $base_namespace;
		$this->base_dir = $base_dir;
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/**
	 * Autoloader
	 *
	 * @param string $class
	 */
	public function autoload( string $class ) {

		/* Afbreken als het geen relevante class is */
		if ( strpos( $class, $this->base_namespace ) !== 0 ) {
			return;
		}

		//Basis-namespace verwijderen
		$class = str_replace( $this->base_namespace . '\\', '', $class );

		//Bestandsnaam opbouwen
		$path = str_replace( '\\', '/', $class );

		$file = trailingslashit( $this->base_dir ) . $path . '.php';
		$file = strtolower( str_replace( '_', '-', $file ) );
		if ( file_exists( $file ) ) {
			require_once $file;
			return;
		}
	}
}
