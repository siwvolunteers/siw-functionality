<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\External_Assets\Splide;
use SIW\Util\CSS;

/**
 * Carousel
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Carousel extends Repeater {

	/** Opties voor carousel */
	protected array $options = [
		'type'        => 'loop', // slide/loop/fade
		'speed'       => 1000,
		'perPage'     => 4,
		'gap'         => '10px',
		'autoplay'    => true,
		'interval'    => 3000,
		'breakpoints' => [
			CSS::TABLET_BREAKPOINT => [
				'perPage' => 2,
			],
			CSS::MOBILE_BREAKPOINT => [
				'perPage' => 1,
			],
		],
	];

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'options' => wp_json_encode( $this->options ),
			'slides'  => $this->items,
		];
	}

	/** {@inheritDoc} */
	protected function get_item_defaults(): array {
		return [
			'image'   => null,
			'title'   => null,
			'excerpt' => null,
			'link'    => [
				'text' => null,
				'url'  => null,
			],
		];
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/elements/carousel.css', [ Splide::get_assets_handle() ], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/elements/carousel.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}

	/** {@inheritDoc} */
	public function enqueue_scripts() {
		wp_register_script(
			self::get_assets_handle(),
			SIW_ASSETS_URL . 'js/elements/carousel.js',
			[ Splide::get_assets_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);
		wp_enqueue_script( self::get_assets_handle() );
	}

	public function set_columns( int $columns ): self {
		$this->options['perPage'] = $columns;
		$this->options['breakpoints'][ CSS::TABLET_BREAKPOINT ]['perPage'] = min( 2, $columns );
		return $this;
	}

	public function set_autoplay( bool $autoplay ): self {
		$this->options['autoplay'] = $autoplay;
		return $this;
	}


	/** {@inheritDoc} */
	protected function initialize() {
		$this->add_class( 'splide' );
	}
}
