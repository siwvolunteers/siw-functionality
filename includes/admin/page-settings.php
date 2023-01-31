<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Data\Project_Type;
use SIW\Data\Special_Page;

/**
 * TODO:
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Page_Settings extends Base {

	const SPECIAL_PAGE_META = 'special_page';
	const PROJECT_TYPE_PAGE = 'project_type_page';


	#[Filter( 'display_post_states' )]
	public function add_panels_post_state( array $post_states, \WP_Post $post ): array {
		$special_page = get_post_meta( $post->ID, self::SPECIAL_PAGE_META, true );
		$project_type_page = get_post_meta( $post->ID, self::PROJECT_TYPE_PAGE, true );

		if ( ! empty( $special_page ) ) {
			$post_states[] = Special_Page::tryFrom( $special_page )?->label;
		}
		if ( ! empty( $project_type_page ) ) {
			$post_states[] = Project_Type::tryFrom( $special_page )?->label;
		}
		return $post_states;
	}

	#[Filter( 'rwmb_meta_boxes' )]
	/** Voegt metabox toe */
	public function add_metabox( array $metaboxes ): array {
		$metaboxes[] = [
			'id'         => 'siw_page_settings',
			'title'      => __( 'Pagina-instellingen', 'siw' ),
			'post_types' => [ 'page' ],
			'context'    => 'side',
			'priority'   => 'high',
			'fields'     => [
				[
					'id'      => self::SPECIAL_PAGE_META,
					'name'    => __( 'Speciale pagina', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Special_Page::toArray(),
				],
				[
					'id'      => self::PROJECT_TYPE_PAGE,
					'name'    => __( 'Projecttype-pagina', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Project_Type::toArray(),
				],
			],
		];
		return $metaboxes;
	}
}
