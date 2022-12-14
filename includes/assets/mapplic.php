<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Mapplic interactieve kaart
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://www.mapplic.com/plugin/docs/
 */
class Mapplic implements Style, Script {

	/** Versienummer */
	const VERSION = '7.1';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'mapplic';

	/** Registreert style */
	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/mapplic/css/mapplic.css', [], self::VERSION );
	}

	/** Registreert script */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/mapplic/js/mapplic.js', [ 'jquery' ], self::VERSION, true );
		$mapplic_localization = [
			'more'        => __( 'Meer', 'siw' ),
			'search'      => __( 'Zoeken', 'siw' ),
			'zoomin'      => __( 'Zoom in', 'siw' ),
			'zoomout'     => __( 'Zoom out', 'siw' ),
			'resetzoom'   => __( 'Reset zoom', 'siw' ),
			'levelup'     => __( 'Niveau omhoog', 'siw' ),
			'leveldown'   => __( 'Niveau omlaag', 'siw' ),
			'clearsearch' => __( 'Verwijder zoekopdracht', 'siw' ),
			'closepopup'  => __( 'Sluit popup', 'siw' ),
			'clearfilter' => __( 'Verwijder filter', 'siw' ),
			'iconfile'    => SIW_ASSETS_URL . 'vendor/mapplic/css/images/icons.svg',
		];
		wp_localize_script( self::ASSETS_HANDLE, 'mapplic_localization', $mapplic_localization );
	}
}
