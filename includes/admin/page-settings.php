<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Project_Type;
use SIW\Data\Special_Page;

class Page_Settings extends Base {

	public const SPECIAL_PAGE_META = 'special_page';
	public const PROJECT_TYPE_PAGE_META = 'project_type_page';

	#[Add_Filter( 'display_post_states' )]
	public function add_panels_post_state( array $post_states, \WP_Post $post ): array {
		$special_page = get_post_meta( $post->ID, self::SPECIAL_PAGE_META, true );
		$special_page_label = Special_Page::tryFrom( $special_page )?->label();

		$project_type_page = get_post_meta( $post->ID, self::PROJECT_TYPE_PAGE_META, true );
		$project_type_page_label = Project_Type::tryFrom( $project_type_page )?->label();

		if ( null !== $special_page_label ) {
			$post_states[] = $special_page_label;
		}
		if ( null !== $project_type_page_label ) {
			$post_states[] = $project_type_page_label;
		}
		return $post_states;
	}

	#[Add_Filter( 'rwmb_meta_boxes' )]
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
					'options' => Special_Page::list(),
				],
				[
					'id'      => self::PROJECT_TYPE_PAGE_META,
					'name'    => __( 'Projecttype-pagina', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Project_Type::list(),
				],
			],
		];
		return $metaboxes;
	}
}
