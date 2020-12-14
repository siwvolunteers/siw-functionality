<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;

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
	 */
	protected array $panes=[];

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
		wp_register_script( 'a11y-tablist', SIW_ASSETS_URL . 'vendor/tablist/tablist.js', [], self::TABLIST_VERSION, true );
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
		$template = Template::get_template( 'elements/tablist');
		$parameters = [
			'id'    => uniqid(),
			'panes' => $this->panes,
		];
		return $template->render( $parameters );
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
			'id'          => uniqid(),
			'title'       => $title,
			'content'     => $content,
			'show_button' => $show_button,
			'button_url'  => $button_url,
			'button_text' => $button_text,
		];
	}
}
