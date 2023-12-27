<?php declare(strict_types=1);

namespace SIW\Content\Post_Types;

use SIW\Content\Post\TM_Country as TM_Country_Post;
use SIW\Content\Post_Types\Post_Type;
use SIW\Data\Country;
use SIW\Data\Post_Type_Support;
use SIW\Data\Project_Type;
use SIW\Data\Special_Page;
use SIW\Data\Work_Type;
use SIW\Elements\Interactive_SVG_Map;
use SIW\Elements\Quote;
use SIW\Util\CSS;

/**
 * Wereld (basis) projecten
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class TM_Country extends Post_Type {

	/** {@inheritDoc} */
	protected static function get_dashicon(): string {
		return 'location-alt';
	}

	/** {@inheritDoc} */
	protected static function get_slug(): string {
		return 'wereld-basis-projecten';
	}

	/** {@inheritDoc} */
	protected static function get_singular_label(): string {
		return __( 'Wereld Basis land', 'siw' );
	}

	/** {@inheritDoc} */
	protected static function get_plural_label(): string {
		return __( 'Wereld Basis landen', 'siw' );
	}

	/** {@inheritDoc} */
	protected static function get_post_type_supports(): array {
		return [
			Post_Type_Support::SOCIAL_SHARE,
			Post_Type_Support::CAROUSEL,
		];
	}

	/** {@inheritDoc} */
	protected static function get_admin_columns(): array {
		return [];
	}

	/** {@inheritDoc} */
	protected static function get_site_sortables(): array {
		return [];
	}


	/** {@inheritDoc} */
	protected function get_custom_post( \WP_Post|int $post ): TM_Country_Post {
		return new TM_Country_Post( $post );
	}

	/** {@inheritDoc} */
	public static function get_meta_box_fields(): array {
		$meta_box_fields = [
			[
				'id'          => 'country',
				'name'        => __( 'Land', 'siw' ),
				'type'        => 'select_advanced',
				'required'    => true,
				'options'     => siw_get_countries_list( Country::TAILOR_MADE ),
				'placeholder' => __( 'Selecteer een land', 'siw' ),
			],
			[
				'id'       => 'work_type',
				'name'     => __( 'Soort werk', 'siw' ),
				'type'     => 'checkbox_list',
				'required' => true,
				'options'  => siw_get_work_types_list( Work_Type::TAILOR_MADE, Work_Type::SLUG ),
			],
			[
				'id'       => 'quote',
				'name'     => __( 'Quote', 'siw' ),
				'type'     => 'text',
				'required' => true,
				'size'     => 100,
			],
			[
				'id'       => 'introduction',
				'name'     => __( 'Introductie', 'siw' ),
				'desc'     => __( 'Inclusief beste reistijd', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'       => 'description',
				'name'     => __( 'Beschrijving', 'siw' ),
				'desc'     => __( 'Beschrijf de Wereld Basis projecten in dit land', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'               => 'image',
				'name'             => __( 'Afbeelding', 'siw' ),
				'type'             => 'image_advanced',
				'required'         => true,
				'force_delete'     => true,
				'max_file_uploads' => 1,
				'max_status'       => false,
				'image_size'       => 'thumbnail',
			],
		];
		return $meta_box_fields;
	}

	/** {@inheritDoc} */
	protected function get_taxonomies(): array {
		$taxonomies['continent'] = [
			'names' => [
				'singular' => __( 'Continent', 'siw' ),
				'plural'   => __( 'Continenten', 'siw' ),
				'slug'     => 'wereld-basis-projecten-in',
			],
			'args'  => [
				'query_var' => 'wereld-basis-projecten-in',
			],
		];
		return $taxonomies;
	}

	protected function get_template_variables( string $type, int $post_id ): array {
		$post = new TM_Country_Post( $post_id );

		$template_variables = [
			'tm_country' => $post,
		];

		if ( 'single' === $type ) {
			$template_variables['mapcss'] = CSS::HIDE_ON_MOBILE_CLASS;
			$template_variables['worldmap'] = Interactive_SVG_Map::create()
				->set_map( Interactive_SVG_Map::MAP_WORLD )
				->select_region( $post->get_country()->get_iso_code() )
				->set_focus_region( $post->get_country()->get_iso_code() )
				->set_zoom_max( 2 )
				->generate();
			$template_variables['quote'] = Quote::create()->set_quote( $post->get_quote() )->generate();
			$template_variables['world_basic_page'] = get_permalink( siw_get_project_type_page( Project_Type::WORLD_BASIC() ) );
			$template_variables['child_policy_page'] = get_permalink( siw_get_special_page( Special_Page::CHILD_POLICY() ) );
			$template_variables['image'] = wp_get_attachment_image( $post->get_image_id(), 'large' );
		}

		return $template_variables;
	}

	/** {@inheritDoc} */
	protected function generate_title( array $data, array $postarr ): string {
		return siw_get_country( $postarr['country'] )->get_name();
	}
}
