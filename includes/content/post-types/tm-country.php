<?php declare(strict_types=1);

namespace SIW\Content\Post_Types;

use SIW\Content\Post\TM_Country as TM_Country_Post;
use SIW\Content\Post_Types\Post_Type;
use SIW\Data\Animation\Easing;
use SIW\Data\Animation\Type;
use SIW\Data\Country;
use SIW\Data\Country_Context;
use SIW\Data\Icons\Dashicons;
use SIW\Data\Post_Type_Support;
use SIW\Data\Project_Type;
use SIW\Data\Special_Page;
use SIW\Data\Visibility_Class;
use SIW\Data\Work_Type;
use SIW\Elements\Interactive_SVG_Map;
use SIW\Elements\Quote;

class TM_Country extends Post_Type {

	#[\Override]
	protected static function get_dashicon(): Dashicons {
		return Dashicons::LOCATION_ALT;
	}

	#[\Override]
	protected static function get_slug(): string {
		return 'wereld-basis-projecten';
	}

	#[\Override]
	protected static function get_singular_label(): string {
		return __( 'Wereld Basis land', 'siw' );
	}

	#[\Override]
	protected static function get_plural_label(): string {
		return __( 'Wereld Basis landen', 'siw' );
	}

	#[\Override]
	protected static function get_post_type_supports(): array {
		return [
			Post_Type_Support::SOCIAL_SHARE,
			Post_Type_Support::CAROUSEL,
		];
	}

	#[\Override]
	protected static function get_admin_columns(): array {
		return [];
	}

	#[\Override]
	protected static function get_site_sortables(): array {
		return [];
	}

	#[\Override]
	protected function get_custom_post( \WP_Post|int $post ): TM_Country_Post {
		return new TM_Country_Post( $post );
	}

	#[\Override]
	public static function get_meta_box_fields(): array {
		$meta_box_fields = [
			[
				'id'          => 'country',
				'name'        => __( 'Land', 'siw' ),
				'type'        => 'select_advanced',
				'required'    => true,
				'options'     => Country::filtered_list( Country_Context::WORLD_BASIC ),
				'placeholder' => __( 'Selecteer een land', 'siw' ),
			],
			[
				'id'       => 'work_type',
				'name'     => __( 'Soort werk', 'siw' ),
				'type'     => 'checkbox_list',
				'required' => true,
				'options'  => Work_Type::list(),
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

	#[\Override]
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
			$template_variables['mapcss'] = Visibility_Class::HIDE_ON_MOBILE->value;
			$template_variables['worldmap'] = Interactive_SVG_Map::create()
				->set_map( Interactive_SVG_Map::MAP_WORLD )
				->select_region( $post->get_country()->iso_code() )
				->set_focus_region( $post->get_country()->iso_code() )
				->set_zoom_max( 2 )
				->generate();
			$template_variables['quote'] = Quote::create()->set_quote( $post->get_quote() )->generate();
			$template_variables['world_basic_page'] = get_permalink( Project_Type::WORLD_BASIC->get_page() );
			$template_variables['child_policy_page'] = get_permalink( Special_Page::CHILD_POLICY->get_page() );
			$template_variables['image'] = wp_get_attachment_image( $post->get_image_id(), 'large' );
			$template_variables['animation_duration'] = 1800;
			$template_variables['animation_easing'] = Easing::EASE_OUT_SINE->value;
			$template_variables['animation_type_quote'] = Type::FADE->value;
			$template_variables['animation_type_left'] = Type::SLIDE_LEFT->value;
			$template_variables['animation_type_right'] = Type::SLIDE_RIGHT->value;

		}

		return $template_variables;
	}

	#[\Override]
	protected function generate_title( array $data, array $postarr ): string {
		return Country::tryFrom( $postarr['country'] )?->label() ?? 'land';
	}
}
