<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Admin\Page_Settings;
use SIW\Interfaces\Enums\Labels as I_Enum_Labels;
use SIW\Traits\Enum_List;

enum Project_Type: string implements I_Enum_Labels {

	use Enum_List;

	case WORKCAMPS = 'workcamps';
	case ESC = 'esc';
	case SCHOOL_PROJECTS = 'school_projects';
	case WORLD_BASIC = 'world_basic';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::WORKCAMPS       => __( 'Groepsvrijwilligerswerk', 'siw' ),
			self::ESC             => __( 'ESC (European Solidarity Corps)', 'siw' ),
			self::SCHOOL_PROJECTS => __( 'Scholenprojecten', 'siw' ),
			self::WORLD_BASIC     => __( 'Wereld basis', 'siw' ),
		};
	}

	public function get_page(): \WP_Post {
		/** @var \WP_Post[]|false */
		$pages = get_pages(
			[
				'meta_key'     => Page_Settings::PROJECT_TYPE_PAGE_META,
				'meta_value'   => $this->value,
				'hierarchical' => false,
			]
		);
		// Fallback naar homepagina
		if ( false === $pages || 0 === count( $pages ) ) {
			return get_post( get_option( 'page_on_front' ) );
		}

		$page = reset( $pages );
		return $page;
	}
}
