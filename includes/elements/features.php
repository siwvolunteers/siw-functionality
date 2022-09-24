<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Util\CSS;

/**
 * Class om een overzicht van features met icon en knop te genereren
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Features extends Repeater {

	const ASSETS_HANDLE = 'siw-features';

	/** Aantal kolommen */
	protected int $columns = 3;

	/** Icon size */
	protected int $icon_size = 4;

	/** Achtergrond van icon */
	protected string $icon_background = 'circle';

	/** Zet aantal kolommen */
	public function set_columns( int $columns ): self {
		$this->columns = $columns;
		return $this;
	}

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'features';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'responsive_classes' => CSS::generate_responsive_classes( $this->columns ),
			'features'           => $this->items,
		];
	}

	/** {@inheritDoc} */
	protected function get_item_defaults(): array {
		return [
			'icon'      => '',
			'title'     => '',
			'content'   => '',
			'add_link'  => false,
			'link_url'  => '',
			'link_text' => __( 'Lees meer', 'siw' ),
		];
	}

	/** {@inheritDoc} */
	protected function parse_item( array $item ): array {
		return [
			'icon'    => [
				'has_background'   => true,
				'size'             => $this->icon_size,
				'icon_class'       => $item['icon'],
				'background_class' => $this->icon_background,
			],
			'title'   => $item['title'],
			'content' => $item['content'],
			'button'  => $item['add_link'] ?
				[
					'url'  => $item['link_url'],
					'text' => $item['link_text'],
				] :
				[],
		];
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/features.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/features.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
