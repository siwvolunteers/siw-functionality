<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\External;
use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Klasse om asset (JS/CSS) te registreren
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Asset {

	/** Voegt style toe */
	public function register_style( Style $style ) {
		add_action( 'wp_enqueue_scripts', [ $style, 'register_style' ], 1 );
		add_action( 'admin_enqueue_scripts', [ $style, 'register_style' ], 1 );
	}

	/** Voegt script toe */
	public function register_script( Script $script ) {
		add_action( 'wp_enqueue_scripts', [ $script, 'register_script' ], 1 );
		add_action( 'admin_enqueue_scripts', [ $script, 'register_script' ], 1 );
	}

	/** Registeer asset als extern (voor prefetch en uitsluiten van optimalisatie) */
	public function register_external_asset( External $external ) {
		add_filter( 'rocket_minify_excluded_external_js', fn( array $domains ) => array_merge( $domains, [ $external->get_external_domain() ] ) );
		add_filter( 'rocket_dns_prefetch', fn( array $domains ) => array_merge( $domains, [ $external->get_external_domain() ] ) );
	}
}
