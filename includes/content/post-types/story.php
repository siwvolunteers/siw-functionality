<?php declare(strict_types=1);
namespace SIW\Content\Post_Types;

use SIW\Content\Post\Story as Story_Post;
use SIW\Content\Post_Types\Post_Type;
use SIW\Data\Country;
use SIW\Data\Post_Type_Support;
use SIW\Elements\Quote;

class Story extends Post_Type {

	/** {@inheritDoc} */
	protected static function get_dashicon(): string {
		return 'format-gallery';
	}

	/** {@inheritDoc} */
	protected static function get_slug(): string {
		return 'ervaringen';
	}

	/** {@inheritDoc} */
	protected static function get_singular_label(): string {
		return __( 'Ervaringsverhaal', 'siw' );
	}

	/** {@inheritDoc} */
	protected static function get_plural_label(): string {
		return __( 'Ervaringsverhalen', 'siw' );
	}

	/** {@inheritDoc} */
	protected static function get_post_type_supports(): array {
		return [
			Post_Type_Support::TITLE,
			Post_Type_Support::SOCIAL_SHARE,
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
	protected function get_custom_post( \WP_Post|int $post ): Story_Post {
		return new Story_Post( $post );
	}

	/** {@inheritDoc} */
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
				'options'     => \siw_get_countries_list( Country::ALL ),
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

	/** {@inheritDoc} */
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

	/** {@inheritDoc} */
	protected function get_template_variables( string $type, int $post_id ): array {
		$post = new Story_Post( $post_id );

		$template_variables = [
			'story' => $post,
			'rows'  => array_map( [ $this, 'parse_row' ], $post->get_rows() ),
		];
		return $template_variables;
	}

	protected function parse_row( array $row ): array {
		static $odd = true;
		$row['flex_direction_class'] = $odd ? '' : 'flex-direction-row-reverse';
		$row['content_animation_direction'] = $odd ? 'left' : 'right';
		$row['image_animation_direction'] = $odd ? 'right' : 'left';
		$row['image'] = wp_get_attachment_image( $row['image'][0], 'large' );
		$row['quote'] = Quote::create()->set_quote( $row['quote'] )->generate();
		$odd = ! $odd;
		return $row;
	}

	/** {@inheritDoc} */
	protected function generate_slug( array $data, array $postarr ): string {
		return sprintf(
			'%s-%s-%s',
			$postarr['name'],
			get_term( $postarr['siw_story_project_type'], 'siw_story_project_type' )->name,
			siw_get_country( $postarr['country'] )->get_name()
		);
	}
}
