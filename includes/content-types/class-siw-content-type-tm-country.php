<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * CPT voor Op Maat landen
 * 
 * @package   SIW\Content
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_Content_Type_TM_Country extends SIW_Content_Type {

	/**
	 * {@inheritDoc}
	 */
	protected $post_type = 'tm_country';

	/**
	 * {@inheritDoc}
	 */
	protected $single_slug = 'vrijwilligerswerk-op-maat';

	/**
	 * {@inheritDoc}
	 */
	protected $archive_slug = 'vrijwilligerswerk-op-maat';

	/**
	 * {@inheritDoc}
	 */
	protected $menu_icon = 'dashicons-location-alt';

	/**
	 * {@inheritDoc}
	 */
	protected $sort_by_title = true;

	/**
	 * {@inheritDoc}
	 */
	protected $show_taxonomy_filter = true;

	/**
	 * {@inheritDoc}
	 */
	protected function get_labels() {
		$labels = [
			'name'               => __( 'Op Maat landen', 'siw' ),
			'singular_name'      => __( 'Op Maat land', 'siw' ),
			'add_new'            => __( 'Nieuw Op Maat land', 'siw' ),
			'add_new_item'       => __( 'Voeg Op Maat land toe', 'siw' ),
			'edit_item'          => __( 'Bewerk Op Maat land', 'siw' ),
			'new_item'           => __( 'Nieuw Op Maat land', 'siw' ),
			'all_items'          => __( 'Alle Op Maat landen', 'siw' ),
			'view_item'          => __( 'Bekijk Op Maat land', 'siw' ),
			'search_items'       => __( 'Zoek Op Maat land', 'siw' ),
			'not_found'          => __( 'Geen Op Maat landen gevonden', 'siw' ),
			'not_found_in_trash' => __( 'Geen Op Maat landen gevonden in de prullenbak', 'siw' ),
			'archives'           => __( 'Alle Op Maat landen', 'siw' ),
		];
		return $labels;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_meta_box_fields() {

		$countries = siw_get_countries('tailor_made_projects');
		foreach ( $countries as $country ) {
			$country_options[ $country->get_slug() ] = $country->get_name();
		}
	
		$work_types = siw_get_work_types('tailor_made_projects');
		foreach ( $work_types as $work_type ) {
			$work_type_options[ $work_type->get_slug() ] = $work_type->get_name();
		}

		$meta_box_fields = [
			[
				'id'          => 'country',
				'name'        => __( 'Land', 'siw' ),
				'type'        => 'select_advanced',
				'options'     => $country_options,
				'placeholder' => __( 'Selecteer een land', 'siw' ),
			],
			[
				'id'         => 'continent',
				'name'       => __( 'Continent', 'siw' ),
				'type'       => 'taxonomy',
				'taxonomy'   => 'siw_tm_country_continent',
				'field_type' => 'radio',
				'admin_columns' => [
					'position'   => 'after title',
					'sort'       => true,
					'filterable' => true,
				],
			],
			[
				'id'          => 'work_type',
				'name'        => __( 'Soort werk', 'siw' ),
				'type'        => 'checkbox_list',
				'options'     => $work_type_options,
			],
			[
				'id'       => 'quote',
				'name'     => __( 'Quote', 'siw' ),
				'type'     => 'text',
				'size'     => 100,
			],
			[
				'id'       => 'introduction',
				'name'     => __( 'Introductie', 'siw' ),
				'desc'     => __( 'Inclusief beste reistijd', 'siw'),
				'type'     => 'wysiwyg',
				'required' => true,
				'raw'      => true,
				'options'  => [
					'teeny'         => true,
					'media_buttons' => false,
					'teeny'         => true,
					'textarea_rows' => 5,
				],
			],
			[
				'id'       => 'description',
				'name'     => __( 'Beschrijving', 'siw' ),
				'desc'     => __( 'Beschrijf de Op Maat projecten in dit land', 'siw'),
				'type'     => 'wysiwyg',
				'required' => true,
				'raw'      => true,
				'options'  => [
					'teeny'         => true,
					'media_buttons' => false,
					'teeny'         => true,
					'textarea_rows' => 5,
				],
			],
			[
				'id'               => 'image',
				'name'             => __( 'Afbeelding', 'siw' ),
				'type'             => 'image_advanced',
				'force_delete'     => true,
				'max_file_uploads' => 1,
				'max_status'       => false,
				'image_size'       => 'thumbnail',
			],
		];
		return $meta_box_fields;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_taxonomies() {
		$labels = [
			'name'                       => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
			'singular_name'              => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
			'menu_name'                  => __( 'Continenten', 'siw' ),
			'all_items'                  => __( 'Alle continenten', 'siw' ),
			'add_new_item'               => __( 'Continent toevoegen', 'siw' ),
			'update_item'                => __( 'Continent bijwerken', 'siw' ),
			'view_item'                  => __( 'View Item', 'siw' ),
			'search_items'               => __( 'Zoek continenten', 'siw' ),
			'not_found'                  => __( 'Geen continenten gevonden', 'siw' ),
		];
		$taxonomies[] = [
			'taxonomy' => 'continent',
			'labels'   => $labels,
			'args'     => [],
			'slug'     => 'vrijwilligerswerk-op-maat-in',
		];
		return $taxonomies;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_archive_intro( $archive_type ) {

		$url = SIW_i18n::get_translated_page_url( siw_get_option( 'tailor_made_explanation_page' ) );
		$link = SIW_Formatting::generate_link( $url, __( 'Projecten Op Maat', 'siw' ) );

		switch ( $archive_type ) {
			case 'post_type':
				$archive_intro = [
					esc_html__( 'Hieronder zie je de landenpagina’s van de Projecten op Maat.', 'siw' ),
					esc_html__( 'Per land leggen we uit welke type projecten wij aanbieden.', 'siw' ),
					esc_html__( 'Tijdens onze Projecten Op Maat bepaal je samen met een regiospecialist wat je gaat doen en hoe lang jouw project duurt.', 'siw' ),
					sprintf( esc_html__( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina %s.', 'siw' ), $link ),
				];
				break;
			case 'continent':
				$archive_intro = [
					sprintf( esc_html__( 'Hieronder zie je de landenpagina’s van de Projecten op Maat in %s.', 'siw' ), get_queried_object()->name ),
					esc_html__( 'Per land leggen we uit welke type projecten wij aanbieden.', 'siw' ),
					esc_html__( 'Tijdens onze Projecten Op Maat bepaal je samen met een regiospecialist wat je gaat doen en hoe lang jouw project duurt.', 'siw' ),
					sprintf( esc_html__( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina %s.', 'siw' ), $link ),
				];
				break;
		}

		return $archive_intro;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_single_seo_title( $title ) {
		return "Projecten op Maat in {$title}"; //TODO: i18n
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_archive_seo_title( $title, $archive_type, $term ) {
		switch ( $archive_type ) {
			case 'post_type':
				$title = __( 'Vrijwilligerswerk Op Maat', 'siw' );
				break;
			case 'continent':
				$title = sprintf( __( 'Vrijwilligerswerk Op Maat in %s', 'siw' ), $term->name );
				break;
		}
		return $title;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_single_title( $title ) {
		return "Projecten op Maat in {$title}";
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function get_archive_title( $title, $archive_type ) {
		switch ( $archive_type ) {
			case 'post_type':
				$title = __( 'Vrijwilligerswerk Op Maat', 'siw' );
				break;
			case 'continent':
				$title = sprintf( __( 'Vrijwilligerswerk Op Maat in %s', 'siw' ), $title );
				break;
		}
		return $title;
	}
}
