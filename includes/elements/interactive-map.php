<?php

namespace SIW\Elements;

use SIW\Properties;
use SIW\HTML;
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

	/**
	 * Mapplic versie
	 *
	 * @var string
	 */
	const MAPPLIC_VERSION = '6.0.2';

	/**
	 * URL van Mapplic-bestanden
	 *
	 * @var string
	 */
	protected $mapplic_url = SIW_ASSETS_URL . 'modules/mapplic/';

	/**
	 * ID van kaart
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Bestandsnaam van kaart
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * Inline CSS-regels
	 *
	 * @var array
	 */
	protected $inline_css;

	/**
	 * Gegevens van kaart
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Haalt categorieën op
	 * 
	 * @return array
	 */
	abstract protected function get_categories();

	/**
	 * Geef locaties terug
	 * 
	 * @return array
	 */
	abstract protected function get_locations();

	/**
	 * Geeft alternatieve content voor mobiel terug
	 * 
	 * @return string
	 */
	abstract protected function get_mobile_content();

	/**
	 *  Genereert interactieve kaart
	 */
	public function generate() {
		$this->set_options();

		$this->enqueue_styles();
		$this->enqueue_scripts();

		$attributes = [
			'id'           => uniqid( 'siw-interactive-map-' ),
			'class'        => ['siw-interactive-map', 'mapplic-dark'],
			'data-options' => $this->options,
		];
		$content = HTML::generate_tag( 'div', $attributes, null, true );

		$content = '<div class="hide-on-mobile hide-on-tablet">' . $content . '</div>';
		$content .= '<div class="hide-on-desktop">' . $this->get_mobile_content() . '</div>';
		return $content;
	}

	/**
	 * Zet opties van de kaart
	 */
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
		];
		$this->options = wp_parse_args( $this->options, $default_options );
	}

	/**
	 * Haalt gegevens voor kaart op
	 * 
	 * @return array
	 */
	protected function get_map_data() {
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

	/**
	 * Parset gegevens van categorie
	 *
	 * @param array $category
	 * @return array
	 */
	protected function parse_category( $category ) {
		$default = [
			'id'    => false,
			'title' => false,
			'color' => Properties::PRIMARY_COLOR,
			'show'  => 'false',
		];
		return wp_parse_args( $category, $default );
	}

	/**
	 * Parset de gegevens van locatie
	 */
	protected function parse_location( $location ) {
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

	/**
	 * Voegt de benodigde scripts toe
	 */
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
			'more'     => __( 'Meer', 'siw' ),
			'search'   => __( 'Zoeken', 'siw' ),
			'iconfile' => $this->mapplic_url . 'css/images/icons.svg'
		];
		wp_localize_script( 'mapplic', 'mapplic_localization', $mapplic_localization );
		wp_enqueue_script( 'mapplic' );

		wp_register_script( 'siw-interactive-maps', SIW_ASSETS_URL . 'js/elements/siw-interactive-maps.js', [ 'mapplic', 'jquery' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-interactive-maps' );
	}

	/**
	 * Voegt benodigde styles toe
	 */
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
