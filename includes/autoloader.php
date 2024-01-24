<?php declare(strict_types=1);

namespace SIW;

class Autoloader {

	public function __construct( protected string $root_namespace, protected string $root_directory ) {
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	public function autoload( string $class_name ) {

		if ( ! str_starts_with( $class_name, $this->root_namespace ) ) {
			return;
		}

		$class_name = str_replace( $this->root_namespace . '\\', '', $class_name );

		$path = str_replace( '\\', '/', $class_name );
		$file = strtolower( str_replace( '_', '-', $path ) ) . '.php';
		$file = trailingslashit( $this->root_directory ) . $file;

		if ( file_exists( $file ) ) {
			require_once $file;
			return;
		}
	}
}
