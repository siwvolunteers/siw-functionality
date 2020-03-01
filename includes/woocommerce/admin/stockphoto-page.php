<?php

namespace SIW\WooCommerce\Admin;

use SIW\Async\Process_Stockphoto_Upload;

/**
 * Aanpassing aan admin t.b.v. aanmeldingen
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 */
class Stockphoto_Page {

	/**
	 * Async request voor verwerken van upload
	 *
	 * @var Process_Stockphoto_Upload
	 */
	protected $process_stockphoto_upload;

	/**
	 * Upload-subdirectory voor stockfotos
	 *
	 * @var string
	 */
	protected $upload_subdir = 'groepsprojecten/stockfotos';

	/**
	 * Tijdelijke directory
	 *
	 * @var string
	 */
	protected $temp_dir = WP_CONTENT_DIR . '/uploads/temp/';

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		$self->process_stockphoto_upload = new Process_Stockphoto_Upload();
		
		add_filter( 'mb_settings_pages', [ $self, 'add_page'] ) ;
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_metabox'] );
		add_filter( 'rwmb_stockphotos_after_save_field', [ $self, 'process_uploads'], 10 ,5 );
	}

	/**
	 * Voegt admin-pagina toe
	 *
	 * @param array $pages
	 *
	 * @return array
	 */
	public function add_page( $pages ) {
		$pages[] = [
			'parent'      => 'edit.php?post_type=product',
			'id'          => 'siw-stockphotos',
			'option_name' => 'siw_stockphoto',
			'capability'  => 'edit_products',
			'menu_title'  => __( "Stockfoto's", 'siw' ),
			'message'     => __( "Stockfoto's opgeslagen", 'siw' ),
			'columns'     => 1,
		];
		return $pages;
	}

	public function add_metabox( $metaboxes ) {

		$metaboxes[] = [
			'id'             => 'stockphotos',
			'title'          => __( "Stockfoto's toevoegen", 'siw' ),
			'settings_pages' => 'siw-stockphotos',
			'fields' => [
				[
					'id'         => 'stockphotos',
					'type'       => 'group',
					'clone'      => true,
					'save_field' => false,
					'add_button' => __( 'Stockfoto toevoegen', 'siw' ),
					'fields'     => [
						[
							'id'               => 'file',
							'type'             => 'file',
							'name'             => __( 'Afbeelding', 'siw' ),
							'required'         => true,
							'max_file_uploads' => 1,
							'upload_dir'       => $this->temp_dir,
						],
						[
							'id'          => 'continent',
							'type'        => 'select_advanced',
							'name'        => __( 'Continent', 'siw' ),
							'placeholder' => __( 'Selecteer een continent', 'siw '),
							'options'     => \siw_get_continents( 'array' ),
						],
						[
							'id'          => 'country',
							'type'        => 'select_advanced',
							'name'        => __( 'Land', 'siw' ),
							'placeholder' => __( 'Selecteer een land', 'siw '),
							'options'     => \siw_get_countries( 'all', 'slug', 'array' ),
						],
						[
							'id'          => 'work_type',
							'type'        => 'select_advanced',
							'name'        => __( 'Soort werk', 'siw' ),
							'placeholder' => __( 'Selecteer soort(en) werk', 'siw '),
							'options'     => \siw_get_work_types( 'all', 'slug', 'array' ),
							'multiple'    => true,
						],
					],
				],
			],
		];

		return $metaboxes;
	}

	/**
	 * Verwerk uploads
	 *
	 * @param null $null
	 * @param array $field
	 * @param array $new
	 * @param mixed $old
	 * @param string $object_id
	 * 
	 * @todo check of er tenminste 1 eigenschap gekozen is
	 */
	public function process_uploads( $null, array $field, array $new, $old, string $object_id ) {
		foreach ( $new as $group ) {
			$continent = $group['continent'];
			$country = $group['country'];
			$work_type = $group['work_type'];

			foreach ( $group['file'] as $url ) {
	
				$file = wp_normalize_path( trailingslashit( $this->temp_dir ) . basename( $url ) );

				$data = [
					'file'      => $file,
					'continent' => $continent,
					'country'   => $country,
					'work_type' => $work_type,
				];
				$this->process_stockphoto_upload->data( $data );
				$this->process_stockphoto_upload->dispatch();
			}
		}
	}
}
