<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een overzicht van infoboxes met icon en knop te genereren
 * 
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Infoboxes extends Repeater {
	
	/** Icon size */
	protected int $icon_size = 3;

	/** Achtergrond van icon */
	protected string $icon_background = 'circle';

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'infoboxes';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'infoboxes'  => $this->items,
		];
	}

	/** {@inheritDoc} */
	protected function get_item_defaults(): array {
		return [
			'icon'    => '',
			'title'   => '',
			'content' => '',
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
		];
	}
}
