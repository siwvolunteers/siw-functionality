<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;
use SIW\Util\Links;

/**
 * Class om een Modal te genereren
 * 
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Modal {

	/** Versienummer */
	const MICROMODAL_VERSION = '0.4.6';

	/** ID van modal */
	protected string $id;

	/** Titel van de modal */
	protected string $title;

	/** Inhoud van de modal */
	protected string $content;

	/** Init */
	public function __construct( string $id = null ) {
		$this->enqueue_styles();
		$this->enqueue_scripts();
		$this->id = ( is_null( $id ) ) ? uniqid( 'siw-modal-' ) : "siw-modal-{$id}";
		
		add_action( 'wp_footer', [ $this, 'render_modal'] );
	}

	/** Voegt styles toe */
	protected function enqueue_styles() {
		wp_register_style( 'siw-modal', SIW_ASSETS_URL . 'css/elements/siw-modal.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-modal' );
	}

	/** Voegt scripts toe */
	protected function enqueue_scripts() {
		wp_register_script( 'micromodal', SIW_ASSETS_URL . 'vendor/micromodal/micromodal.js', [], self::MICROMODAL_VERSION, true );
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

	/** Rendert modal */
	public function render_modal() {

		Template::render_template(
			'elements/modal',
			[
				'id' => $this->id,
				'title' => $this->title,
				'content' => $this->content,
				'i18n' => [
					'close' => __( 'Sluiten', 'siw' ),
				]
			]
		);
	}

	/** Zet de titel van de modal */
	public function set_title( string $title ) {
		$this->title = $title;
	}

	/** Genereert link voor modal */
	public function generate_link( string $text, string $link = null ) : string {
		$link = Links::generate_link(
			$link ?? '#',
			$text,
			[ 'data-micromodal-trigger' => $this->id, 'target' => '_blank' ] //TODO: optie voor target?
		);
		return $link;
	}

	/** Zet inhoud van modal */
	public function set_content( string $content ) {
		$this->content = $content;
	}

	/** Geeft gegenereerde id van modal terug */
	public function get_id() : string {
		return $this->id;
	}
}
