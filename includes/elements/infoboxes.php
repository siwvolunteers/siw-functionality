<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;
use SIW\Util\CSS;

/**
 * Class om een overzicht van infoboxes met icon en knop te genereren
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
class Infoboxes {
	
	/**
	 * Icon size
	 */
	protected int $icon_size = 3;

	/**
	 * Achtergrond van icon
	 */
	protected string $icon_background = 'circle';

	/**
	 * Infoboxes
	 */
	protected array $infoboxes;

	/**
	 *  function
	 *
	 * @param string $icon
	 * @param string $title
	 * @param string $content
	 * @param string $url
	 * @param string $text
	 */
	public function add_infobox( string $icon, string $title, string $content ) {
	
		//Afbreken als content geen zichtbare inhoud bevat
		if ( 0 === strlen( trim( $content ) ) ) {
			return;
		}

		$this->infoboxes[] = [
			'icon'     => [
				'has_background'   => true,
				'size'             => $this->icon_size,
				'icon_class'       => $icon,
				'background_class' => $this->icon_background,
			],
			'title'    => $title,
			'content'  => $content,
		];
	}
	
	/**
	 * Genereert features
	 *
	 * @return string
	 */
	public function generate() : string {
		return Template::parse_template(
			'elements/infoboxes',
			[
				'infoboxes'  => $this->infoboxes,
			]
		);
	}
}
