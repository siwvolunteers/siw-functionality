<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;
use SIW\Util\CSS;

/**
 * Class om een overzicht van features met icon en knop te genereren
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
class Features {
	
	/**
	 * Aantal kolommen
	 */
	protected int $columns;

	/**
	 * Icon size
	 */
	protected int $icon_size = 4;

	/**
	 * Achtergrond van icon
	 */
	protected string $icon_background = 'circle';

	/**
	 * Features
	 */
	protected array $features = [];

	/**
	 * Undocumented function
	 *
	 * @param int $columns
	 */
	public function __construct( int $columns ) {
		$this->columns = $columns;
	}

	/**
	 * Undocumented function
	 *
	 * @param string $icon
	 * @param string $title
	 * @param string $content
	 * @param string $url
	 * @param string $text
	 */
	public function add_feature( string $icon, string $title, string $content, bool $show_button = false, string $button_url = null, string $button_text = null ) {
	
		//Afbreken als content geen zichtbare inhoud bevat
		if ( 0 === strlen( trim( $content ) ) ) {
			return;
		}

		$this->features[] = [
			'icon'     => [
				'has_background'   => true,
				'size'             => $this->icon_size,
				'icon_class'       => $icon,
				'background_class' => $this->icon_background,
			],
			'title'    => $title,
			'content'  => $content,
			'button'   => $show_button ?
				[ 'url'  => $button_url, 'text' => $button_text ] :
				[],
		];
	}
	
	/**
	 * Genereert features
	 *
	 * @return string
	 */
	public function generate() : string {
		return Template::parse_template(
			'elements/features',
			[
				'responsive_classes' => CSS::generate_responsive_classes( $this->columns ),
				'features'           => $this->features
			]
		);
	}
}
