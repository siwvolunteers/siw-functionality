<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;

/**
 * Class om een accordion te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://github.com/AcceDe-Web/accordion
 */
class Accordion {

	/** Versienummer */
	const ACCORDION_VERSION = '1.1.0';

	/** Panes */
	protected array $panes = [];

	/** Init */
	public function __construct() {
		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	/** Genereert accordion */
	public function generate() : string {
		$template = Template::get_template( 'elements/accordion');
		$parameters = [
			'id'    => uniqid(),
			'panes' => $this->panes,
		];
		return $template->render( $parameters );
	}

	/** Voegt scripts toe */
	protected function enqueue_scripts() {
		wp_register_script( 'a11y-accordion', SIW_ASSETS_URL . 'vendor/accordion/accordion.js', [], self::ACCORDION_VERSION, true );
		wp_register_script( 'siw-accordion', SIW_ASSETS_URL . 'js/elements/siw-accordion.js', ['a11y-accordion'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-accordion' );
	}

	/** Voegt styles toe */
	protected function enqueue_styles() {
		wp_register_style( 'siw-accordion', SIW_ASSETS_URL . 'css/elements/siw-accordion.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-accordion' );
	}

	/** Voegt pane aan accordion toe */
	public function add_pane( string $title, string $content, bool $show_button = false, string $button_url = null, string $button_text = null ) {
		
		//Afbreken als content geen zichtbare inhoud bevat
		if ( 0 === strlen( trim( $content ) ) ) {
			return;
		}

		$this->panes[] = [
			'id'       => uniqid(),
			'title'    => $title,
			'content'  => $content,
			'button'   => $show_button ?
				[ 'url'  => $button_url, 'text' => $button_text ] :
				[],
		];
	}
}
