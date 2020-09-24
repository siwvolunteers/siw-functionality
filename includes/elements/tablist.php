<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\HTML;
use SIW\Util\Links;

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
		wp_register_script( 'siw-tablist', SIW_ASSETS_URL . 'js/elements/siw-tablist.js', ['a11y-tablist'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-tablist');
	}

	/**
	 * Voegt styles toe
	 */
	protected function enqueue_styles() {
		wp_register_style( 'siw-tablist', SIW_ASSETS_URL . 'css/elements/siw-tablist.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-tablist' );
	}

	/**
	 * Genereert tablist
	 *
	 * @return string
	 */
	public function generate() : string {
		$attributes = [
			'id'    => uniqid( 'siw-tablist-' ),
			'class' => ['siw-tablist'],
		];
		return HTML::div( $attributes, $this->generate_panes() );
	} 

	/**
	 * Genereert panes voor tablist
	 *
	 * @return string
	 * 
	 * @todo generate_tag/generate_list gebruiken
	 */
	protected function generate_panes() : string {
		$list = '<ul role="tablist">';
		$content = '';
		foreach ( $this->panes as $pane ) {
			$id = uniqid();

			if ( isset( $pane['show_button'] ) && true == $pane['show_button'] ) {
				$pane['content'] .= wpautop( Links::generate_button_link( $pane['button_url'], $pane['button_text'] ) );
			}

			$list .= sprintf( '<li role="tab" aria-controls="tab-%s">%s</li>', $id, esc_html( $pane['title'] ) );
			$content .= sprintf( '<div role="tabpanel" id="tab-%s">%s</div>', $id, wp_kses_post( wpautop( $pane['content'] ) ) );
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
	 * @param string $button_url
	 * @param string $button_text
	 */
	public function add_pane( string $title, string $content, bool $show_button = false, string $button_url = null, string $button_text = null ) {
		
		//Afbreken als content geen zichtbare inhoud bevat
		if ( 0 === strlen( trim( $content ) ) ) {
			return;
		}

		$this->panes[] = [
			'title'       => $title,
			'content'     => $content,
			'show_button' => $show_button,
			'button_url'  => $button_url,
			'button_text' => $button_text,
		];
	}
}
