<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Admin\Page_Settings;
use SIW\Interfaces\Enums\Labels as I_Enum_Labels;
use SIW\Traits\Enum_List;

enum Special_Page: string implements I_Enum_Labels {

	use Enum_List;

	case CONTACT = 'contact';
	case CHILD_POLICY = 'child_policy';
	case NEWSLETTER_CONFIRMATION = 'newsletter_confirmation';

	public function label(): string {
		return match ( $this ) {
			self::CONTACT                 => __( 'Contact', 'siw' ),
			self::CHILD_POLICY            => __( 'Kinderbeleid', 'siw' ),
			self::NEWSLETTER_CONFIRMATION => __( 'Bevestiging nieuwsbrief', 'siw' ),
		};
	}

	public function get_page(): \WP_Post {
		/** @var \WP_Post[]|false */
		$pages = get_pages(
			[
				'meta_key'     => Page_Settings::SPECIAL_PAGE_META,
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
