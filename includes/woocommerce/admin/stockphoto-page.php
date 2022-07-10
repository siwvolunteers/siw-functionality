<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Data\Country;
use SIW\Data\Work_Type;

/**
 * Aanpassing aan admin t.b.v. aanmeldingen
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Stockphoto_Page {

	/** Pagina-ID */
	protected string $page_id = 'siw-stockphotos';

	/** Upload-subdirectory voor stockfotos */
	protected string $upload_subdir = 'groepsprojecten/stockfotos';

	/** Tijdelijke directory */
	protected string $temp_dir = WP_CONTENT_DIR . '/uploads/temp/';

	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'mb_settings_pages', [ $self, 'add_page' ] );
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_metabox' ] );
		add_filter( 'rwmb_stockphotos_after_save_field', [ $self, 'process_uploads' ], 10, 5 );
		add_action( 'admin_menu', [ $self, 'add_woocommerce_navigation_bar' ] );
	}

	/** Voegt admin-pagina toe */
	public function add_page( $pages ) : array {
		$pages[] = [
			'parent'      => 'edit.php?post_type=product',
			'id'          => $this->page_id,
			'option_name' => 'siw_stockphoto',
			'capability'  => 'edit_products',
			'menu_title'  => __( "Stockfoto's", 'siw' ),
			'message'     => __( "Stockfoto's opgeslagen", 'siw' ),
			'columns'     => 1,
		];
		return $pages;
	}

	/** Voegt metabox toe */
	public function add_metabox( array $metaboxes ) : array {

		$metaboxes[] = [
			'id'             => 'stockphotos',
			'title'          => __( "Stockfoto's toevoegen", 'siw' ),
			'settings_pages' => $this->page_id,
			'fields'         => [
				[
					'id'         => 'stockphotos',
					'type'       => 'group',
					'clone'      => true, // TODO: of lager
					'max_clone'  => 5,
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
							'placeholder' => __( 'Selecteer een continent', 'siw' ),
							'options'     => \siw_get_continents_list(),
						],
						[
							'id'          => 'country',
							'type'        => 'select_advanced',
							'name'        => __( 'Land', 'siw' ),
							'placeholder' => __( 'Selecteer een land', 'siw' ),
							'options'     => \siw_get_countries_list( Country::ALL, 'slug' ),
							'required'    => true,

						],
						[
							'id'          => 'work_types',
							'type'        => 'select_advanced',
							'name'        => __( 'Soort werk', 'siw' ),
							'placeholder' => __( 'Selecteer soort(en) werk', 'siw' ),
							'options'     => \siw_get_work_types_list( Work_Type::ALL, Work_Type::SLUG ),
							'multiple'    => true,
							'required'    => true,
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
	 * @todo check of er tenminste 1 eigenschap gekozen is
	 */
	public function process_uploads( $null, array $field, array $new, $old, string $object_id ) {
		foreach ( $new as $group ) {
			$continent = $group['continent'];
			$country = $group['country'];
			$work_types = $group['work_types'];

			foreach ( $group['file'] as $url ) {

				$file = wp_normalize_path( trailingslashit( $this->temp_dir ) . basename( $url ) );

				$data = [
					'file'       => $file,
					'continent'  => $continent,
					'country'    => $country,
					'work_types' => $work_types,
				];

				siw_enqueue_async_action( 'process_stockphoto_upload', $data );
			}
		}
	}

	/** Voegt WooCommerce navigatiebalk toe */
	public function add_woocommerce_navigation_bar() {
		if ( ! function_exists( '\wc_admin_connect_page' ) ) {
			return;
		}

		\wc_admin_connect_page(
			[
				'id'        => 'siw-stockphotos',
				'parent'    => 'woocommerce-products',
				'screen_id' => "product_page_{$this->page_id}",
				'title'     => __( "Stockfoto's", 'siw' ),
			]
		);
	}
}
