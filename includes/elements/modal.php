<?php

namespace SIW\Elements;

use SIW\HTML;

/**
 * Class om een Modal te genereren
 * 
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Modal {

	/**
	 * Versienummer
	 * 
	 * @var string
	 */
	const MICROMODAL_VERSION = '0.4.2';

	/**
	 * ID van modal
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Titel van de modal
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Inhoud van de modal
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * Init
	 *
	 * @param string $id
	 */
	public function __construct( string $id = null ) {
		$this->enqueue_styles();
		$this->enqueue_scripts();
		$this->id = ( null === $id ) ? uniqid( 'siw-modal-' ) : "siw-modal-{$id}";
		
		add_action( 'wp_footer', [ $this, 'render_modal'] );

		//Tijdelijke fix
		add_filter( 'rocket_exclude_js', [ $this, 'exclude_js_from_combine' ] );
	}

	/**
	 * Voegt styles toe
	 */
	protected function enqueue_styles() {
		wp_register_style( 'siw-modal', SIW_ASSETS_URL . 'css/elements/siw-modal.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-modal' );
	}

	/**
	 * Voegt scripts toe
	 */
	protected function enqueue_scripts() {
		wp_register_script( 'micromodal', SIW_ASSETS_URL . 'modules/micromodal/micromodal.js', [], self::MICROMODAL_VERSION, true );
		wp_register_script( 'siw-modal', SIW_ASSETS_URL . 'js/elements/siw-modal.js', [ 'micromodal' ], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			'siw-modal',
			'siw_modal',
			[
				'openTrigger'         => 'data-modal-open',
				'closeTrigger'        => 'data-modal-close',
				'disableScroll'       => true,
				'disableFocus'        => false,
				'awaitOpenAnimation'  => true,
				'awaitCloseAnimation' => true,
				'debugMode'           => defined( 'WP_DEBUG' ) && WP_DEBUG,
		]);
		wp_enqueue_script( 'siw-modal' );
	}

	/**
	 * JS-bestanden uitsluiten van minification/concatenation
	 *
	 * @param array $excluded_files
	 * @return array
	 */
	public function exclude_js_from_combine( array $excluded_files ) {
		$excluded_files[] = wp_make_link_relative( SIW_ASSETS_URL . 'modules/micromodal/micromodal.js' );
		$excluded_files[] = wp_make_link_relative( SIW_ASSETS_URL . 'js/elements/siw-modal.js' );
		return $excluded_files;
	}


	/**
	 * Rendert modal
	 */
	public function render_modal() {
		$modal = sprintf( '<div class="modal micromodal-slide" id="%s" aria-hidden="true">', $this->id );
		$modal .= '<div class="modal-overlay" tabindex="-1" data-micromodal-close>';
		$modal .= sprintf( '<div class="modal-container" role="dialog" aria-modal="true" aria-labelledby="%s-title">', $this->id );
		$modal .= $this->generate_header() . $this->generate_body() . $this->generate_footer();
		$modal .= '</div>';
		$modal .= '</div>';
		$modal .= '</div>';
		$modal .= '</div>';
		echo $modal;
	}

	/**
	 * Genereert titel van de modal
	 *
	 * @return string
	 */
	protected function generate_header() {
		$header = '<header class="modal-header">';
		$header .= sprintf( '<h4 class="modal-title" id="%s-title">%s</h4>', $this->id, $this->title );
		$header .= sprintf( '<button class="modal-close" aria-label="%s" data-micromodal-close></button>', esc_html__( 'Sluiten', 'siw' ) );
		$header .= '</header>';

		return $header;
	}

	/**
	 * Genereert body van de modal
	 *
	 * @return string
	 */
	protected function generate_body() {
		return HTML::generate_tag(
			'main',
			[
				'class' => 'modal-body',
				'id'    => "{$this->id}-content"
			],
			wpautop( wp_kses_post( $this->content ) ),
			true
		);
	}

	/**
	 * Genereert footer van de modal
	 *
	 * @return string
	 */
	protected function generate_footer() {
		$button = sprintf(
			'<button class="kad-btn" data-micromodal-close aria-label="%s">%s</button>',
			esc_html__( 'Sluit deze dialoog', 'siw' ),
			esc_html__( 'Sluiten', 'siw' )
		);
		return HTML::generate_tag( 'footer', [ 'class' => 'modal-footer'], $button, true );
	}

	/**
	 * Zet de titel van de modal
	 *
	 * @param string $title
	 */
	public function set_title( string $title ) {
		$this->title = $title;
	}

	/**
	 * Genereert link voor modal
	 *
	 * @param string $text
	 * @param string $link
	 *
	 * @return string
	 */
	public function generate_link( string $text, string $link = null ) {
		$link = HTML::generate_link(
			$link ?? '#',
			$text,
			[ 'data-micromodal-trigger' => $this->id, 'target' => '_blank' ] //TODO: optie voor target?
		);
		return $link;
	}

	/**
	 * Zet inhoud van modal
	 *
	 * @param string $content
	 */
	public function set_content( string $content ) {
		$this->content = $content;
	}

	/**
	 * Geeft gegenereerde id van modal terug
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

}
