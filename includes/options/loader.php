<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Abstracts\Loader as Loader_Abstract;

/**
 * Class om opties te laden
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Loader extends Loader_Abstract {

	/**
	 * {@inheritDoc}
	 */
	protected string $id = 'options';
	
	/**
	 * {@inheritDoc}
	 */
	protected string $interface_namespace = 'Options';

	/**
	 * {@inheritDoc}
	 */
	protected array $classes = [
		'Configuration',
		'Countries',
		'Settings'
	];

	/**
	 * {@inheritDoc}
	 */
	protected function load( object $option ) {
		if( ! $this->implements_interface( $option, 'Option' ) ) {
			return;
		}
		new Option( $option );
	}
}