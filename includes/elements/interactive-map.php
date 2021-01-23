<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;
use SIW\Properties;
use SIW\Util\CSS;
use SIW\Util;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://www.mapplic.com/plugin/docs/
 */
abstract class Interactive_Map {

	/** Mapplic versie */
	const MAPPLIC_VERSION = '6.1.3';

	/** URL van Mapplic-bestanden */
	protected string $mapplic_url = SIW_ASSETS_URL . 'vendor/mapplic/';

	/** ID van kaart */
	protected string $id;

	/** Bestandsnaam van kaart */
	protected string $file;

	/** Inline CSS-regels */
	protected array $inline_css;

	/** Gegevens van kaart */
	protected array $options;

	/** Haalt categorieën op */
	abstract protected function get_categories() : array;

	/** Geef locaties terug */
	abstract protected function get_locations() : array;

	/** Geeft alternatieve content voor mobiel terug */
	abstract protected function get_mobile_content() : ?string;

	/**  Genereert interactieve kaart */
	public function generate() : string {
		$this->set_options();

		$this->enqueue_styles();
		$this->enqueue_scripts();

		return Template::parse_template(
			'elements/interactive-map',
			[
				'id'                    => uniqid(),
				'options'               => $this->options,
				'hide_on_desktop_class' => CSS::HIDE_ON_DESKTOP_CLASS,
				'hide_on_tablet_class'  => CSS::HIDE_ON_TABLET_CLASS,
				'hide_on_mobile_class'  => CSS::HIDE_ON_MOBILE_CLASS,
				'mobile_content'        => $this->get_mobile_content(),
			]
		);
	}

	/** Zet opties van de kaart */
	protected function set_options() {
		$default_options = [
			'source'        => $this->get_map_data(),
			'landmark'      => null,
			'portrait'      => Util::get_mobile_breakpoint(),
			'alphabetic'    => true,
			'search'        => false,
			'lightbox'      => false,
			'deeplinking'   => false,
			'zoombuttons'   => false,
			'zoomoutclose'  => true,
			'mousewheel'    => false,
			'fullscreen'    => false,
			'developer'     => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'fillcolor'     => Properties::PRIMARY_COLOR,
			'action'        => 'tooltip',
			'maxscale'      => 2,
			'hovertipdesc'  => true,
			'animation'     => true,
		];
		$this->options = wp_parse_args( $this->options, $default_options );
	}

	/** Haalt gegevens voor kaart op */
	protected function get_map_data() : array {
		$default_data = [
			'mapwidth'  => null,
			'mapheight' => null,
			'bottomLat' => '',
			'leftLng'   => '',
			'topLat'    => '',
			'rightLng'  => '',
		];
		$data = wp_parse_args( $this->data, $default_data );
		
		$data['categories'] = array_map( [ $this, 'parse_category'], $this->get_categories() );
		$data['levels'][] = [
			'id'        => $this->id,
			'title'     => $this->id,
			'map'       => $this->mapplic_url . 'maps/' . $this->file . '.svg', 
			'locations' => array_map( [ $this, 'parse_location'], $this->get_locations() ),
		];
		return $data;
	}

	/** Parset gegevens van categorie */
	protected function parse_category( array $category ) : array {
		$default = [
			'id'    => false,
			'title' => false,
			'color' => Properties::PRIMARY_COLOR,
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
			'fill'          => Properties::PRIMARY_COLOR,
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
		if ( true == $this->options[ 'lightbox' ] ) {
			wp_register_script( 'magnific-popup', $this->mapplic_url . 'js/magnific-popup.js', [ 'jquery' ], self::MAPPLIC_VERSION, true );
			$deps[] = 'magnific-popup';
		}
		if ( true == $this->options[ 'mousewheel' ] ) {
			wp_register_script( 'mousewheel', $this->mapplic_url . 'js/jquery.mousewheel.js', [ 'jquery' ], self::MAPPLIC_VERSION, true );
			$deps[] = 'mousewheel';
		}
		wp_register_script( 'mapplic', $this->mapplic_url . 'js/mapplic.js', $deps, self::MAPPLIC_VERSION, true );

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
		wp_localize_script( 'mapplic', 'mapplic_localization', $mapplic_localization );
		wp_enqueue_script( 'mapplic' );

		wp_register_script( 'siw-interactive-maps', SIW_ASSETS_URL . 'js/elements/siw-interactive-maps.js', [ 'mapplic', 'jquery' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-interactive-maps' );
	}

	/** Voegt benodigde styles toe */
	protected function enqueue_styles() {
		$deps = [];
		if ( true == $this->options[ 'lightbox' ] ) {
			wp_register_style( 'magnific-popup', $this->mapplic_url . 'css/magnific-popup.css', [], self::MAPPLIC_VERSION );
			$deps[] = 'magnific-popup';
		}
		wp_register_style( 'mapplic', $this->mapplic_url . 'css/mapplic.css', $deps, self::MAPPLIC_VERSION );
		wp_enqueue_style( 'mapplic' );


		wp_register_style( 'siw-interactive-map', SIW_ASSETS_URL . 'css/elements/siw-interactive-map.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-interactive-map' );

		if ( isset( $this->inline_css ) ) {
			$css = CSS::generate_inline_css( $this->inline_css );
			wp_add_inline_style( 'siw-interactive-map', $css );
		}
	}
}
