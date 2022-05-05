<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Assets\Micromodal;
use SIW\Util\Links;

/**
 * Class om een Modal te genereren
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://micromodal.now.sh/
 */
class Modal extends Element {

	/** Handle voor assets */
	const ASSETS_HANDLE = 'siw-modal';

	/** Titel van de modal */
	protected string $title;

	/** Inhoud van de modal */
	protected string $content;

	/** Init */
	protected function initialize() {
		add_action( 'wp_footer', [ $this, 'render' ] );
	}

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'modal';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'id'      => $this->get_element_id(),
			'title'   => $this->title,
			'content' => $this->content,
			'i18n'    => [
				'close' => __( 'Sluiten', 'siw' ),
			],
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
	public function set_page( int $page_id ) {
		$page = get_post( $page_id );
		$this->title = $page->post_title;
		$this->content = do_shortcode( $page->post_content );

		// Overschrijf modal id
		$this->element_id = "siw-modal-{$page_id}";
		return $this;
	}

	/** Genereert link voor modal */
	public function generate_link( string $text, string $link = null ): string {
		$link = Links::generate_link(
			$link ?? '#',
			$text,
			[
				'data-micromodal-trigger' => $this->get_element_id(),
				'target'                  => '_blank',
			] // TODO: optie voor target?
		);
		return $link;
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/siw-modal.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/elements/siw-modal.js', [ Micromodal::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::ASSETS_HANDLE,
			'siw_modal',
			[
				'openTrigger'         => 'data-modal-open',
				'closeTrigger'        => 'data-modal-close',
				'disableScroll'       => true,
				'disableFocus'        => false,
				'awaitOpenAnimation'  => true,
				'awaitCloseAnimation' => true,
				'debugMode'           => defined( 'WP_DEBUG' ) && WP_DEBUG,
			]
		);
		wp_enqueue_script( self::ASSETS_HANDLE );
	}
}
