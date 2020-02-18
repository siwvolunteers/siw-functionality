<?php

namespace SIW\Elements;

use SIW\HTML;

/**
 * Class om een tablist te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://github.com/AcceDe-Web/tablist
 */
class Tablist {
	
	/**
	 * Versienummer
	 * 
	 * @var string
	 */
	const TABLIST_VERSION = '2.0.1';

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
	 * Voegt scripts toe
	 */
	protected function enqueue_scripts() {
		wp_register_script( 'a11y-tablist', SIW_ASSETS_URL . 'modules/tablist/tablist.js', [], self::TABLIST_VERSION, true );
		wp_register_script( 'siw-tablist', SIW_ASSETS_URL . 'js/siw-tablist.js', ['a11y-tablist'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-tablist');
	}

	/**
	 * Voegt styles toe
	 */
	protected function enqueue_styles() {
		wp_register_style( 'siw-tablist', SIW_ASSETS_URL . 'css/siw-tablist.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-tablist' );
	}

	/**
	 * Genereert tablist
	 *
	 * @return string
	 */
	public function generate() {
		$attributes = [
			'id'    => uniqid( 'siw-tablist-' ),
			'class' => ['siw-tablist'],
		];
		return HTML::generate_tag( 'div', $attributes ) . $this->generate_panes() . '</div>' ;
	} 

	/**
	 * Genereert panes voor tablist
	 *
	 * @return string
	 * 
	 * @todo generate_tag/generate_list gebruiken
	 */
	protected function generate_panes() {
		$list = '<ul role="tablist">';
		$content = '';
		foreach ( $this->panes as $pane ) {
			$id = uniqid();

			if ( isset( $pane['show_button'] ) && true == $pane['show_button'] ) {
				$pane['content'] .= wpautop( HTML::generate_link( $pane['button_url'], $pane['button_text'], [ 'class' => 'kad-btn' ] ) );
			}

			$list .= sprintf( '<li role="tab" aria-controls="tab-%s">%s</li>', $id, $pane['title'] );
			$content .= sprintf( '<div role="tabpanel" id="tab-%s">%s</div>', $id, $pane['content'] );
		}

		$list .= '</ul>';

		return $list . $content;
	}

	/**
	 * Voegt pane aan tablist toe
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