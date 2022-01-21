<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Interfaces\Elements\Interactive_Map as Interactive_Map_Interface;

use SIW\Util\CSS;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://www.mapplic.com/plugin/docs/
 */
class Interactive_Map extends Element {

	// Constantes voor script/style handles
	const SCRIPT_HANDLE = 'siw-interactive-maps';
	const MAPPLIC_SCRIPT_HANDLE = 'mapplic';
	const MAPPLIC_STYLE_HANDLE = 'mapplic';
	const MAGNIFIC_POPUP_SCRIPT_HANDLE = 'magnific-popup';
	const MAGNIFIC_POPUP_STYLE_HANDLE = 'magnific-popup';
	const MOUSEWHEEL_SCRIPT_HANDLE = 'mousewheel';

	/** Mapplic versie */
	const MAPPLIC_VERSION = '7.1';

	/** URL van Mapplic-bestanden */
	protected string $mapplic_url = SIW_ASSETS_URL . 'vendor/mapplic/';

	/** Interactive kaart */
	protected Interactive_Map_Interface $interactive_map;

	/** Init */
	public function set_interactive_map( Interactive_Map_Interface $interactive_map ) {
		$this->interactive_map = $interactive_map;
		return $this;
	}

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'interactive-map';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'id'                    => uniqid(),
			'options'               => $this->get_options(),
			'hide_on_desktop_class' => CSS::HIDE_ON_DESKTOP_CLASS,
			'hide_on_tablet_class'  => CSS::HIDE_ON_TABLET_CLASS,
			'hide_on_mobile_class'  => CSS::HIDE_ON_MOBILE_CLASS,
			'mobile_content'        => $this->interactive_map->get_mobile_content(),
		];
	}

	/** Zet opties van de kaart */
	protected function get_options() : array {
		$options = wp_cache_get( $this->interactive_map->get_id(), __METHOD__ );
		if ( false !== $options ) {
			return $options;
		}

		$default_options = [
			'source'        => $this->get_source_data(),
			'landmark'      => null,
			'portrait'      => CSS::MOBILE_BREAKPOINT,
			'alphabetic'    => true,
			'search'        => false,
			'lightbox'      => false,
			'deeplinking'   => false,
			'zoombuttons'   => false,
			'zoomoutclose'  => true,
			'mousewheel'    => false,
			'fullscreen'    => false,
			'developer'     => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'fillcolor'     => CSS::ACCENT_COLOR,
			'action'        => 'tooltip',
			'maxscale'      => 2,
			'hovertipdesc'  => true,
			'animation'     => true,
		];

		$options = wp_parse_args( $this->interactive_map->get_options(), $default_options );
		wp_cache_set( $this->interactive_map->get_id(), $options, __METHOD__ );
		return $options;
	}

	/** Geeft optie terug */
	protected function get_option( string $option ) {
		$options = $this->get_options();
		return $options[ $option ] ?? null;
	}

	/** Haalt gegevens voor kaart op */
	protected function get_source_data() : array {
		$default_data = [
			'mapwidth'  => null,
			'mapheight' => null,
			'bottomLat' => '',
			'leftLng'   => '',
			'topLat'    => '',
			'rightLng'  => '',
		];
		$data = wp_parse_args( $this->interactive_map->get_map_data(), $default_data );
		
		$data['categories'] = array_map( [ $this, 'parse_category'], $this->interactive_map->get_categories() );
		$data['levels'][] = [
			'id'        => $this->interactive_map->get_id(),
			'title'     => $this->interactive_map->get_id(),
			'map'       => $this->mapplic_url . 'maps/' . $this->interactive_map->get_file() . '.svg', 
			'locations' => array_map( [ $this, 'parse_location'], $this->interactive_map->get_locations() ),
		];
		return $data;
	}

	/** Parset gegevens van categorie */
	protected function parse_category( array $category ) : array {
		$default = [
			'id'    => false,
			'title' => false,
			'color' => CSS::ACCENT_COLOR,
			'show'  => 'false',
		];
		return wp_parse_args( $category, $default );
	}

	/** Parset de gegevens van locatie */
	protected function parse_location( array $location ) : array {
		$default = [
			'id'            => false,
			'title'         => false,
			'image'         => null,
			'about'         => false,
			'description'   => false,
			'action'        => 'tooltip',
			'pin'           => 'hidden',
			'fill'          => CSS::ACCENT_COLOR,
			'x'             => null,
			'y'             => null,
			'lat'           => false,
			'lng'           => false,
			'pin'           => 'hidden',
			'category'      => false,
		];
		return wp_parse_args( $location, $default );
	}

	/** Voegt de benodigde scripts toe */
	protected function enqueue_scripts() {
		$deps = [ 'jquery' ];
		if ( true == $this->get_option( 'lightbox' ) ) {
			wp_register_script( self::MAGNIFIC_POPUP_SCRIPT_HANDLE, $this->mapplic_url . 'js/magnific-popup.js', [ 'jquery' ], self::MAPPLIC_VERSION, true );
			$deps[] = self::MAGNIFIC_POPUP_SCRIPT_HANDLE;
		}
		if ( true == $this->get_option( 'mousewheel' ) ) {
			wp_register_script( self::MOUSEWHEEL_SCRIPT_HANDLE, $this->mapplic_url . 'js/jquery.mousewheel.js', [ 'jquery' ], self::MAPPLIC_VERSION, true );
			$deps[] = self::MOUSEWHEEL_SCRIPT_HANDLE;
		}
		wp_register_script( self::MAPPLIC_SCRIPT_HANDLE, $this->mapplic_url . 'js/mapplic.js', $deps, self::MAPPLIC_VERSION, true );

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
			'iconfile'    => $this->mapplic_url . 'css/images/icons.svg'
		];
		wp_localize_script( self::MAPPLIC_SCRIPT_HANDLE, 'mapplic_localization', $mapplic_localization );
		wp_enqueue_script( self::MAPPLIC_SCRIPT_HANDLE );

		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'js/elements/siw-interactive-maps.js', [ self::MAPPLIC_SCRIPT_HANDLE, 'jquery' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	/** Voegt benodigde styles toe */
	protected function enqueue_styles() {
		$deps = [];
		if ( true == $this->get_option( 'lightbox' ) ) {
			wp_register_style( self::MAGNIFIC_POPUP_STYLE_HANDLE, $this->mapplic_url . 'css/magnific-popup.css', [], self::MAPPLIC_VERSION );
			$deps[] = self::MAGNIFIC_POPUP_STYLE_HANDLE;
		}
		wp_register_style( self::MAPPLIC_STYLE_HANDLE, $this->mapplic_url . 'css/mapplic.css', $deps, self::MAPPLIC_VERSION );
		wp_enqueue_style( self::MAPPLIC_STYLE_HANDLE );
	}
}
