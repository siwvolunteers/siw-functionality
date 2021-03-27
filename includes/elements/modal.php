<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Util\Links;

/**
 * Class om een Modal te genereren
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://micromodal.now.sh/
 */
class Modal extends Element {

	/** Versienummer */
	const MICROMODAL_VERSION = '0.4.6';

	/** ID van pagina voor modal */
	protected string $modal_id;

	/** Titel van de modal */
	protected string $title;

	/** Inhoud van de modal */
	protected string $content;

	/** Init */
	protected function __construct() {
		$this->modal_id = uniqid( 'siw-modal-' );
		add_action( 'wp_footer', [ $this, 'render'] );
	}

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'modal';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'id'      => $this->modal_id,
			'title'   => $this->title,
			'content' => $this->content,
			'i18n'    => [
				'close' => __( 'Sluiten', 'siw' ),
			]
		];
	}

	/** Zet de titel van de modal */
	public function set_title( string $title ) {
		$this->title = $title;
	}

	/** Zet inhoud van modal */
	public function set_content( string $content ) {
		$this->content = $content;
	}

	/** Zet pagina van de modal */
	public function set_page( int $page_id ) : self {
		$page = get_post( $page_id );
		$this->title = $page->post_title;
		$this->content = do_shortcode( $page->post_content );

		//Overschrijf modal id
		$this->modal_id = "siw-modal-{$page_id}";
		return $this;
	}

	/** Genereert link voor modal */
	public function generate_link( string $text, string $link = null ) : string {
		$link = Links::generate_link(
			$link ?? '#',
			$text,
			[ 'data-micromodal-trigger' => $this->modal_id, 'target' => '_blank' ] //TODO: optie voor target?
		);
		return $link;
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
}
