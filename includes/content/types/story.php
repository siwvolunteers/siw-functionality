<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Data\Country_Context;
use SIW\Data\Project_Type;
use SIW\Elements\Quote;
use SIW\Helpers\Template;
use SIW\Util\HTML;
use SIW\Util\Links;

/**
 * Ervaringsverhalen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Story extends Type {

	/** {@inheritDoc} */
	protected string $post_type = 'story';

	/** {@inheritDoc} */
	protected string $menu_icon = 'dashicons-format-gallery';

	/** {@inheritDoc} */
	protected string $slug = 'ervaringen';

	/** {@inheritDoc} */
	protected bool $archive_taxonomy_filter = true;

	/** {@inheritDoc} */
	protected bool $archive_masonry = true;

	/** {@inheritDoc} */
	protected int $archive_column_width = 33;

	/** {@inheritDoc} */
	protected string $upload_subdir = 'ervaringen';

	/** {@inheritDoc} */
	public function get_meta_box_fields(): array {
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
				'options'     => \siw_get_countries_list( Country_Context::ALL ),
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
			'labels' => [
				'name'          => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
				'singular_name' => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'     => __( 'Continenten', 'siw' ),
				'all_items'     => __( 'Alle continenten', 'siw' ),
				'add_new_item'  => __( 'Continent toevoegen', 'siw' ),
				'update_item'   => __( 'Continent bijwerken', 'siw' ),
				'view_item'     => __( 'View Item', 'siw' ),
				'search_items'  => __( 'Zoek continenten', 'siw' ),
				'not_found'     => __( 'Geen continenten gevonden', 'siw' ),
			],
			'args'   => [
				'public' => true,
			],
			'slug'   => 'ervaringen-uit',
			'filter' => true,
		];
		$taxonomies['project_type'] = [
			'labels' => [
				'name'          => _x( 'Projectsoort', 'Taxonomy General Name', 'siw' ),
				'singular_name' => _x( 'Projectsoort', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'     => __( 'Projectsoort', 'siw' ),
				'all_items'     => __( 'Alle projectsoorten', 'siw' ),
				'add_new_item'  => __( 'Projectsoort toevoegen', 'siw' ),
				'update_item'   => __( 'Projectsoort bijwerken', 'siw' ),
				'view_item'     => __( 'Bekijk projectsoort', 'siw' ),
				'search_items'  => __( 'Zoek projectsoorten', 'siw' ),
				'not_found'     => __( 'Geen projectsoorten gevonden', 'siw' ),
			],
			'args'   => [
				'public' => true,
			],
			'slug'   => 'ervaringen-over',
			'filter' => true,
		];
		return $taxonomies;
	}

	/** {@inheritDoc} */
	protected function get_labels(): array {
		$labels = [
			'name'          => __( 'Ervaringsverhalen', 'siw' ),
			'singular_name' => __( 'Ervaringsverhaal', 'siw' ),
			'add_new'       => __( 'Nieuw ervaringsverhaal', 'siw' ),
			'add_new_item'  => __( 'Nieuw ervaringsverhaal toevoegen', 'siw' ),
			'edit_item'     => __( 'Ervaringsverhaal bewerken', 'siw' ),
			'all_items'     => __( 'Alle ervaringsverhalen', 'siw' ),
			'search_items'  => __( 'Ervaringsverhalen zoeken', 'siw' ),
			'not_found'     => __( 'Geen ervaringsverhalen gevonden', 'siw' ),
		];
		return $labels;
	}

	/** {@inheritDoc} */
	public function add_archive_content() {
		$rows = siw_meta( 'rows' );
		$continent = siw_meta( 'siw_story_continent' );
		$project_type = siw_meta( 'siw_story_project_type' );
		$template_vars = [
			'image'     => wp_get_attachment_image( $rows[0]['image'][0], 'large' ),
			'link'      => Links::generate_button_link( get_permalink(), __( 'Lees meer', 'siw' ) ),
			'project'   => $project_type->name,
			'continent' => $continent->name,
		];
		Template::create()->set_template( 'types/story_archive' )->set_context( $template_vars )->render_template();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @todo refactor enzo
	 */
	public function add_single_content() {
		$animation_fade = HTML::generate_attributes(
			[
				'data-sal'          => 'fade',
				'data-sal-duration' => 1800,
				'data-sal-easing'   => 'ease-out-sine',
				'data-sal-delay'    => 'none',
			]
		);
		$animation_left = HTML::generate_attributes(
			[
				'data-sal'          => 'slide-left',
				'data-sal-duration' => 1800,
				'data-sal-easing'   => 'ease-out-sine',
				'data-sal-delay'    => 'none',
			]
		);
		$animation_right = HTML::generate_attributes(
			[
				'data-sal'          => 'slide-right',
				'data-sal-duration' => 1800,
				'data-sal-easing'   => 'ease-out-sine',
				'data-sal-delay'    => 'none',
			]
		);
		$template_vars = [
			'project_type'   => siw_meta( 'siw_story_project_type' )->name,
			'cta'            => $this->get_cta_url(),
			'animation_fade' => $animation_fade,
		];
		$stories = [];
		$rows = siw_meta( 'rows' );
		$even = false;
		foreach ( $rows as $row ) {
			$story = [
				'quote'                  => Quote::create()->set_quote( $row['quote'] )->generate(),
				'flex_direction_class'   => $even ? 'flex-direction-row-reverse' : '',
				'animation_attributes_1' => $even ? $animation_left : $animation_right,
				'animation_attributes_2' => $even ? $animation_right : $animation_left,
				'image'                  => wp_get_attachment_image( $row['image'][0], 'large' ),
			];
			$content = [];
			foreach ( $row['content'] as $paragraph ) {
				array_push(
					$content,
					[
						'title' => $paragraph['title'],
						'text'  => $paragraph['text'],
					]
				);
			}
			$story += [ 'content' => $content ];
			array_push( $stories, $story );
			$even = ! $even;
		}
		$template_vars += [ 'stories' => $stories ];
		Template::create()->set_template( 'types/story_single' )->set_context( $template_vars )->render_template();
	}

	/** Bepaal een call to action link ahv projecttype of continent */
	protected function get_cta_url(): ?string {

		$pages = [
			'esc'            => Project_Type::ESC,
			'wereld-basis'   => Project_Type::WORLD_BASIC,
			'scholenproject' => Project_Type::SCHOOL_PROJECTS,
			'groepsproject'  => Project_Type::WORKCAMPS,
		];

		$project_type = siw_meta( 'siw_story_project_type' );
		$page = $pages[ $project_type->slug ] ?? null;

		if ( null === $page ) {
			return null;
		}

		$project_type_page = siw_get_project_type_page( $page );

		if ( null === $project_type_page ) {
			return null;
		}

		return Links::generate_button_link( get_permalink( $project_type_page ), __( 'Bekijk de mogelijkheden', 'siw' ) );
	}

	/** {@inheritDoc} */
	protected function get_archive_intro(): array {
		$intro = siw_get_option( 'story.archive_intro' );
		return [ $intro ];
	}

	/** {@inheritDoc} */
	protected function get_social_share_cta(): string {
		return __( 'Deel dit ervaringsverhaal', 'siw' );
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
