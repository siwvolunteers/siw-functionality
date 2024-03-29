<?php declare(strict_types=1);
namespace SIW\Content\Post_Types;

use SIW\Content\Post\Story as Story_Post;
use SIW\Content\Post_Types\Post_Type;
use SIW\Data\Animation\Easing;
use SIW\Data\Animation\Type;
use SIW\Data\Country;
use SIW\Data\Icons\Dashicons;
use SIW\Data\Post_Type_Support;
use SIW\Elements\Quote;

class Story extends Post_Type {

	#[\Override]
	protected static function get_dashicon(): Dashicons {
		return Dashicons::FORMAT_GALLERY;
	}

	#[\Override]
	protected static function get_slug(): string {
		return 'ervaringen';
	}

	#[\Override]
	protected static function get_singular_label(): string {
		return __( 'Ervaringsverhaal', 'siw' );
	}

	#[\Override]
	protected static function get_plural_label(): string {
		return __( 'Ervaringsverhalen', 'siw' );
	}

	#[\Override]
	protected static function get_post_type_supports(): array {
		return [
			Post_Type_Support::TITLE,
			Post_Type_Support::SOCIAL_SHARE,
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
	protected function get_custom_post( \WP_Post|int $post ): Story_Post {
		return new Story_Post( $post );
	}

	#[\Override]
	public static function get_meta_box_fields(): array {
		$meta_box_fields = [
			[
				'type' => 'heading',
				'name' => __( 'Gegevens', 'siw' ),
			],
			[
				'id'       => 'name',
				'name'     => __( 'Voornaam', 'siw' ),
				'type'     => 'text',
				'required' => true,
			],
			[
				'id'          => 'country',
				'name'        => __( 'Land', 'siw' ),
				'type'        => 'select_advanced',
				'required'    => true,
				'options'     => Country::list(),
				'placeholder' => __( 'Selecteer een land', 'siw' ),
			],
			[
				'type' => 'heading',
				'name' => __( 'Inhoud', 'siw' ),
			],
			[
				'id'         => 'rows',
				'type'       => 'group',
				'clone'      => true,
				'sort_clone' => false,
				'add_button' => __( 'Rij toevoegen', 'siw' ),
				'fields'     => [
					[
						'id'        => 'quote',
						'name'      => __( 'Quote', 'siw' ),
						'type'      => 'text',
						'required'  => true,
						'size'      => 100,
						'maxlength' => 250,
					],
					[
						'id'               => 'image',
						'name'             => __( 'Afbeelding', 'siw' ),
						'type'             => 'image_advanced',
						'required'         => true,
						'force_delete'     => false,
						'max_file_uploads' => 1,
						'max_status'       => false,
						'image_size'       => 'thumbnail',
					],
					[
						'id'          => 'content',
						'name'        => __( 'Inhoud', 'siw' ),
						'type'        => 'group',
						'add_button'  => __( 'Paragraaf toevoegen', 'siw' ),
						'collapsible' => true,
						'group_title' => '{title}',
						'clone'       => true,
						'sort_clone'  => false,
						'fields'      => [
							[
								'id'       => 'title',
								'name'     => __( 'Titel', 'siw' ),
								'type'     => 'text',
								'required' => true,
								'size'     => 100,
							],
							[
								'id'       => 'text',
								'name'     => __( 'Tekst', 'siw' ),
								'type'     => 'wysiwyg',
								'required' => true,
							],
						],
					],
				],
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
				'slug'     => 'ervaringen-uit',
			],
			'args'  => [
				'query_var' => 'ervaringen-uit',
			],
		];
		$taxonomies['project_type'] = [
			'names' => [
				'singular' => __( 'Projectsoort', 'siw' ),
				'plural'   => __( 'Projectsoorten', 'siw' ),
				'slug'     => 'ervaringen-over',
			],
			'args'  => [
				'query_var' => 'ervaringen-over',
			],
		];
		return $taxonomies;
	}

	#[\Override]
	protected function get_template_variables( string $type, int $post_id ): array {
		$post = new Story_Post( $post_id );

		$template_variables = [
			'story'                => $post,
			'rows'                 => array_map( [ $this, 'parse_row' ], $post->get_rows() ),
			'animation'            => [
				'easing'   => Easing::EASE_IN_OUT_SINE->value,
				'duration' => 1800,
			],
			'quote_animation_type' => Type::FADE->value,
		];
		return $template_variables;
	}

	protected function parse_row( array $row ): array {
		static $odd = true;
		$row['flex_direction_class'] = $odd ? '' : 'flex-direction-row-reverse';
		$row['content_animation_type'] = $odd ? Type::SLIDE_LEFT->value : Type::SLIDE_RIGHT->value;
		$row['image_animation_type'] = $odd ? Type::SLIDE_RIGHT->value : Type::SLIDE_LEFT->value;
		$row['image'] = wp_get_attachment_image( $row['image'][0], 'large' );
		$row['quote'] = Quote::create()->set_quote( $row['quote'] )->generate();
		$odd = ! $odd;
		return $row;
	}
}
