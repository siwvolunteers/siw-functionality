<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Breakpoint;
use SIW\External_Assets\Splide;

class Carousel extends Repeater {

	protected array $options = [
		'type'        => 'loop', // slide/loop/fade
		'speed'       => 1000,
		'perPage'     => 4,
		'gap'         => '10px',
		'autoplay'    => true,
		'pagination'  => false,
		'interval'    => 3000,
		'breakpoints' => [
			Breakpoint::TABLET->value => [
				'perPage' => 2,
			],
			Breakpoint::MOBILE => [
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
		self::enqueue_class_style( [ Splide::get_asset_handle() ] );
	}

	/** {@inheritDoc} */
	public function enqueue_scripts() {
		self::enqueue_class_script( [ Splide::get_asset_handle() ] );
	}

	public function set_columns( int $columns ): self {
		$this->options['perPage'] = $columns;
		$this->options['breakpoints'][ Breakpoint::TABLET->value ]['perPage'] = min( 2, $columns );
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
