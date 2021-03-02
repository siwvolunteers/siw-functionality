<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Data\Country;
use SIW\Elements;
use SIW\HTML;
use SIW\Util\Links;

/**
 * Ervaringsverhalen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.?
 */
class Story extends Type {

	/**
	 * {@inheritDoc}
	 */
	protected string $post_type = 'story';

	/**
	 * {@inheritDoc}
	 */
	protected string $menu_icon = 'dashicons-format-gallery';

	/**
	 * {@inheritDoc}
	 */
	protected string $slug = 'ervaringen';

	/**
	 * {@inheritDoc}
	 */
	protected bool $archive_taxonomy_filter = true;

	/**
	 * {@inheritDoc}
	 */
	protected bool $archive_masonry = true;

	/**
	 * {@inheritDoc}
	 */
	protected int $archive_column_width = 33;

	/**
	 * {@inheritDoc}
	 */
	protected string $upload_subdir = 'ervaringen';

	/**
	 * {@inheritDoc}
	 */
	public function get_meta_box_fields() : array {
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
				'options'     => \siw_get_countries_list( Country::ALL, 'slug' ),
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
						'id'       => 'quote',
						'name'     => __( 'Quote', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'size'     => 100,
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

	/**
	 * {@inheritDoc}
	 */
	protected function get_taxonomies() : array {
		$taxonomies['continent'] = [
			'labels' => [
				'name'                       => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
				'singular_name'              => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'                  => __( 'Continenten', 'siw' ),
				'all_items'                  => __( 'Alle continenten', 'siw' ),
				'add_new_item'               => __( 'Continent toevoegen', 'siw' ),
				'update_item'                => __( 'Continent bijwerken', 'siw' ),
				'view_item'                  => __( 'View Item', 'siw' ),
				'search_items'               => __( 'Zoek continenten', 'siw' ),
				'not_found'                  => __( 'Geen continenten gevonden', 'siw' ),
			],
			'args' => [
				'public' => true,
			],
			'slug'   => 'ervaringen-uit',
			'filter' => true,
		];
		$taxonomies['project_type'] = [
			'labels' => [
				'name'                       => _x( 'Projectsoort', 'Taxonomy General Name', 'siw' ),
				'singular_name'              => _x( 'Projectsoort', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'                  => __( 'Projectsoort', 'siw' ),
				'all_items'                  => __( 'Alle projectsoorten', 'siw' ),
				'add_new_item'               => __( 'Projectsoort toevoegen', 'siw' ),
				'update_item'                => __( 'Projectsoort bijwerken', 'siw' ),
				'view_item'                  => __( 'Bekijk projectsoort', 'siw' ),
				'search_items'               => __( 'Zoek projectsoorten', 'siw' ),
				'not_found'                  => __( 'Geen projectsoorten gevonden', 'siw' ),
			],
			'args' => [
				'public' => true,
			],
			'slug'   => 'ervaringen-over',
			'filter' => true,
		];
		return $taxonomies;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_labels() : array {
		$labels = [
			'name'               => __( 'Ervaringsverhalen', 'siw' ),
			'singular_name'      => __( 'Ervaringsverhaal', 'siw' ),
			'add_new'            => __( 'Nieuw ervaringsverhaal', 'siw' ),
			'add_new_item'       => __( 'Nieuw ervaringsverhaal toevoegen', 'siw' ),
			'edit_item'          => __( 'Ervaringsverhaal bewerken', 'siw' ),
			'all_items'          => __( 'Alle ervaringsverhalen', 'siw' ),
			'search_items'       => __( 'Ervaringsverhalen zoeken', 'siw' ),
			'not_found'          => __( 'Geen ervaringsverhalen gevonden', 'siw' ),
		];
		return $labels;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_archive_content() {

		$rows = siw_meta( 'rows' );
		$continent = siw_meta( 'siw_story_continent');
		$project_type = siw_meta( 'siw_story_project_type');

		?>
		<div class="grid-100">
			<?php echo wp_get_attachment_image( $rows[0]['image'][0], 'large'); ?>
		</div>
		<div class="grid-100">
			<?php the_excerpt(); ?>
		</div>
		<div class="grid-100">
			<?php echo Links::generate_button_link( get_permalink() , __( 'Lees meer', 'siw' ) );?>
		</div>
		<hr>
		<div class="grid-100">
			<?php printf( '%s | %s', esc_html( $continent->name ), esc_html( $project_type->name ) ); ?>
		</div>
		<?php
	}

	/**
	 * {@inheritDoc}
	 * 
	 * @todo refactor enzo
	 */
	public function add_single_content() {
		$rows = siw_meta( 'rows' );
	
		$even = false;
	
		//TODO: classes niet hardcoden enzo
		foreach ( $rows as $row ) {
			$push_class = $even ? 'push-60' : '';
			$pull_class = $even ? 'pull-40' : '';
			
			//TODO: fatsoenlijk
			$animation_fade = HTML::generate_attributes( ['data-sal' => 'fade', 'data-sal-duration' => 1800, 'data-sal-easing' => 'ease-out-sine', 'data-sal-delay' => 'none']);
			$animation_left = HTML::generate_attributes( ['data-sal' => 'slide-left', 'data-sal-duration' => 1800, 'data-sal-easing' => 'ease-out-sine', 'data-sal-delay' => 'none']);
			$animation_right = HTML::generate_attributes( ['data-sal' => 'slide-right', 'data-sal-duration' => 1800, 'data-sal-easing' => 'ease-out-sine', 'data-sal-delay' => 'none']);
			$animation_attributes_1 = $even ? $animation_left : $animation_right;
			$animation_attributes_2 = $even ? $animation_right : $animation_left;
			?>
			<div class="grid-100" <?php echo $animation_fade;?> >
				<?php echo Elements::generate_quote( $row['quote'] );?>
			</div>
	
			<div class="grid-40 <?php echo $push_class;?>" <?php echo $animation_attributes_1;?>>
				<?php echo wp_get_attachment_image( $row['image'][0], 'large'); ?>
			</div>
			<div class="grid-60 <?php echo $pull_class;?>"<?php echo $animation_attributes_2;?> >
				<?php foreach ( $row['content'] as $paragraph ) : ?>
				<b><?php echo esc_html( $paragraph['title'] );?></b>
				<?php echo wpautop( wp_kses_post( $paragraph['text'] ) ); ?>
				<?php endforeach; ?>
			</div>
			<?php 
				$even = ! $even;
		}
		?>
		<!-- Start CTA (TODO) -->



		<!-- Eind -->
		<?php
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_archive_intro() : array {
		$intro = [
			__( 'Simone organiseerde Nederlandse taalles voor asielzoekers in België, Jacky stak de handen uit de mouwen op een oesterkwekerij in Japan, en Daphne werkte in een herberg voor migranten in Mexico.', 'siw' ),
			__( 'Waar ga jij het liefst aan de slag?', 'siw' ),
			__( 'We vinden het belangrijk om onze vrijwilligers zo goed mogelijk voor te bereiden op hun avontuur in een andere cultuur.', 'siw' ),
			__( 'En wie kunnen nu beter uitleggen hoe onze projecten in elkaar zitten dan onze oud-deelnemers?', 'siw' ),
			__( 'Lees hier per regio of per type project de ervaringsverhalen van oud-deelnemers en laat je inspireren!', 'siw' ),
		];
		return $intro;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_social_share_cta() : string {
		return __( 'Deel dit ervaringsverhaal', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function generate_slug( array $data, array $postarr ): string {
		return sprintf(
			'%s-%s-%s',
			$postarr['name'],
			get_term( $postarr['siw_story_project_type'], 'siw_story_project_type' )->name,
			siw_get_country( $postarr['country'] )->get_name()
		);
	}
}
