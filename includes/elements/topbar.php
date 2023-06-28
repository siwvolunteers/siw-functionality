<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Topbar
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Topbar extends Element {

	const ASSETS_HANDLE = 'siw-topbar';

	/** URL */
	protected string $url;

	/** Text */
	protected string $text;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'topbar';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'url'       => $this->url,
			'text'      => $this->text,
			'ga4_event' => [
				'name'       => 'click',
				'parameters' => [
					'link_id'  => 'topbar',
					'link_url' => $this->url,
					'outbound' => false,
				],
			],
		];
	}

	public function set_url( string $url ): self {
		$this->url = $url;
		return $this;
	}

	public function set_text( string $text ): self {
		$this->text = $text;
		return $this;
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/topbar.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/topbar.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
