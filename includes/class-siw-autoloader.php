<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Autoloader voor SIW classes
 * 
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_Autoloader {

	/**
	 * Basis-prefix
	 *
	 * @var string
	 */
	protected $base_prefix = 'SIW_';

	/**
	 * Basisdirectory
	 *
	 * @var string
	 */
	protected $base_dir;

	/**
	 * Directories
	 *
	 * @var array
	 */
	protected $dirs = [];

	/**
	 * Registreert autoloader
	 */
	public function register() {
		$this->add_dir( '', '' );
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Zet base directory
	 *
	 * @param string $base_dir
	 */
	public function set_base_dir( $base_dir ) {
		$this->base_dir = trailingslashit( $base_dir );
	}

	/**
	 * Voegt directory toe
	 *
	 * @param string $dir
	 * @param string $prefix
	 */
	public function add_dir( $dir, $prefix ) {
		$this->dirs[] = array(
			'dir'    => trailingslashit( $this->base_dir . $dir ),
			'prefix' => $this->base_prefix . $prefix,
		);
	}

	/**
	 * Autoloader
	 *
	 * @param string $class
	 */
	public function autoload( $class ) {

		/* Afbreken als het geen SIW class is */
		if ( 0 !== strpos( $class, $this->base_prefix ) ) {
			return;
		}

		foreach ( $this->dirs as $dir ) {
			if ( $dir['prefix'] && 0 !== strpos( $class, $dir['prefix'] ) ) {
				continue;
			}
			$file = strtolower( str_replace( '_', '-', $class ) ) . '.php';
			$file = 'class-' . $file;
			$file = $dir['dir'] . $file;
			if ( file_exists( $file ) ) {
				require_once $file;
				break;
			}
		}
	}
}