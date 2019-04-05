<?php
/**
 * Metabox voor evenementen
 * 
 * @package    SIW
 * @author     Maarten Bruna
 * @copyright  2019 SIW Internationale Vrijwilligersprojecten
 * */

add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {

	$countries = siw_get_countries('tailor_made_projects');
	foreach ( $countries as $country ) {
		$country_options[ $country->get_slug() ] = $country->get_name();
	}

	$work_types = siw_get_work_types( 'tailor_made_projects');
	foreach ( $work_types as $work_type ) {
		$work_type_options[ $work_type->get_slug() ] = $work_type->get_name();
	}

	$meta_boxes[] = [
		'id'          => 'siw',
		'title'       => __( 'Op maat land', 'siw' ),
		'post_types'  => 'siw_tm_country',
		'toggle_type' => 'slide',
		'context'     => 'normal',
		'priority'    => 'high',
		'fields' => [
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
		],
	];
	return $meta_boxes;
});