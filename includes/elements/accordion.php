<?php

namespace SIW\Elements;

use SIW\HTML;

/**
 * Class om een accordion te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://github.com/AcceDe-Web/accordion
 */
class Accordion {

	/**
	 * Versienummer
	 * 
	 * @var string
	 */
	const ACCORDION_VERSION = '1.1.0';

	/**
	 * Panes
	 *
	 * @var array
	 */
	protected $panes=[];

	/**
	 * Init
	 */
	public function __construct() {
		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	/**
	 * Genereert accordion
	 *
	 * @return string
	 */
	public function generate() {
		$attributes = [
			'id'                   => uniqid( 'siw-accordion-' ),
			'class'                => ['siw-accordion'],
			'data-role'            => 'accordion',
			'data-multiselectable' => 'true',

		];
		return HTML::generate_tag( 'div', $attributes, $this->generate_panes(), true );
	}

	/**
	 * Voegt scripts toe
	 */
	protected function enqueue_scripts() {
		wp_register_script( 'a11y-accordion', SIW_ASSETS_URL . 'modules/accordion/accordion.js', [], self::ACCORDION_VERSION, true );
		wp_register_script( 'siw-accordion', SIW_ASSETS_URL . 'js/siw-accordion.js', ['a11y-accordion'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-accordion');
	}

	/**
	 * Voegt styles toe
	 */
	protected function enqueue_styles() {
		wp_register_style( 'siw-accordion', SIW_ASSETS_URL . 'css/siw-accordion.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-accordion' );
	}

	/**
	 * Genereert panes voor accordion
	 *
	 * @return string
	 */
	protected function generate_panes() {
		$output = '';
		foreach ( $this->panes as $pane ) {
			$id = uniqid();

			if ( isset( $pane['show_button'] ) && true == $pane['show_button'] ) {
				$pane['content'] .= wpautop( HTML::generate_link( $pane['button_url'], $pane['button_text'], [ 'class' => 'kad-btn' ] ) );
			}

			$output .= implode( '', 
				[
					'<h5 class="tab">',
					sprintf('<span id="tab%s" role="button" aria-controls="panel%s">%s</span>', $id, $id, esc_html( $pane['title'] ) ),
					'</h5>',
					sprintf( '<div class="panel" id="panel%s" aria-labelledby="tab%s" style="max-height:0px">', $id, $id ),
					wp_kses_post( wpautop( $pane['content'] ) ),
					'</div>'
				]
			);
		}
		return $output;
	}

	/**
	 * Voegt pane aan accordion toe
	 *
	 * @param string $title
	 * @param string $content
	 * @param bool $show_button
	 * @param string $button_text
	 * @param string $button_link
	 */
	public function add_pane( string $title, string $content, bool $show_button = false, string $button_link = null, string $button_text = null ) {
		
		//Afbreken als content geen zichtbare inhoud bevat
		if ( 0 === strlen( trim( $content ) ) ) {
			return;
		}

		$this->panes[] = [
			'title'       => $title,
			'content'     => $content,
			'show_button' => $show_button,
			'button_link' => $button_link,
			'button_text' => $button_text,
		];
	}
}
