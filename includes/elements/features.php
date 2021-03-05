<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Util\CSS;

/**
 * Class om een overzicht van features met icon en knop te genereren
 * 
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Features extends Repeater {
	
	/** Aantal kolommen */
	protected int $columns = 3;

	/** Icon size */
	protected int $icon_size = 4;

	/** Achtergrond van icon */
	protected string $icon_background = 'circle';

	public function set_columns( int $columns ) {
		$this->columns = $columns;
	}

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'features';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'responsive_classes' => CSS::generate_responsive_classes( $this->columns ),
			'features'           => $this->items
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
			'icon'     => [
				'has_background'   => true,
				'size'             => $this->icon_size,
				'icon_class'       => $item['icon'],
				'background_class' => $this->icon_background,
			],
			'title'    => $item['title'],
			'content'  => $item['content'],
			'button'   => $item['add_link'] ?
				[ 'url'  => $item['link_url'], 'text' => $item['link_text'] ] :
				[],
		];
	}
}
