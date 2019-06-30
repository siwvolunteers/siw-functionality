<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Import van afbeelding voor een Groepsproject
 *
 * @author    Maarten Bruna
 * @package   SIW\WooCommerce
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_WC_Import_Product_Image {

	/**
	 * Basis-directory
	 *
	 * @var string
	 */
	protected $base_dir;

	/**
	 * Basis-url
	 *
	 * @var string
	 */
	protected $base_url;

	/**
	 * Subdirectory voor projectfoto's binnen uploaddirectory
	 *
	 * @var string
	 */
	protected $subdir = '/wpallimport/files/'; //'/groepsprojecten/';

	/**
	 * Land van het project
	 *
	 * @var SIW_Country
	 */
	protected $country;

	/**
	 * Soorten werk van het project
	 *
	 * @var SIW_Work_Type[]
	 */
	protected $work_types;

	/**
	 * Toegestane bestandsextensies
	 *
	 * @var array
	 */
	protected $extensions = [
		'jpg',
		'png',
		'gif',
	];

	/**
	 * Constructor
	 *
	 * @param SIW_Country $country
	 * @param array $work_types
	 */
	public function __construct( SIW_Country $country, array $work_types ) {
		$this->country = $country;
		$this->work_types = $work_types;
		
		$upload_dir = wp_upload_dir( null, false );
		$this->base_dir = $upload_dir['basedir'] . $this->subdir;
		$this->base_url = $upload_dir['baseurl'] . $this->subdir;
	}

	/**
	 * Geeft image id terug
	 * 
	 * @return int|null
	 */
	public function get_image_id() {
		$path = $this->select_image();
		if ( null == $path ) {
			return null;
		}

		$image_id = attachment_url_to_postid( $this->base_url . $path );
		if ( 0 != $image_id ) {
			return $image_id;
		}
		$image_id = $this->add_attachment( $path );
		return $image_id;
	}

	/**
	 * Selecteert afbeelding op basis van land en soort werk
	 * 
	 * @return string|null
	 */
	protected function select_image() {
		$extensions = implode( '|', $this->extensions );

		$patterns = $this->get_patterns();

		foreach ( $patterns as $pattern ) {
			// Zoek bestanden
			$files = glob( $this->base_dir . $pattern, GLOB_BRACE );

			//Ontdubbelen
			$files = array_unique( $files );

			//Relatief pad van maken
			array_walk( $files, function(&$value, &$key) {
				$value = str_replace( $this->base_dir, '', $value );
			});

			// Verschillende image sizes eruit filteren bv. image-120x120.jpg
			$files = array_filter( $files, function( $file ) use( $extensions ) {
				return ! (bool) preg_match( "/.+-[0-9]{2,3}x[0-9]{2,3}\.({$extensions})$/i", $file );
			});

			//Random afbeelding uit resultaten kiezen
			if ( sizeof( $files ) > 0 ) {
				return $files[ array_rand( $files, 1 ) ];
			}
		}
		return null;
	}
	
	/**
	 * Voegt attachment toe aan database
	 *
	 * @param string $path
	 * @return int
	 */
	protected function add_attachment( $path ) {

		$file = $this->base_dir . $path;
		$url = $this->base_url . $path;
		
		$image_id = wp_insert_attachment( [
			'guid'           => $url, 
			'post_mime_type' => wp_get_image_mime( $file ),
			'post_title'     => 'Projectfoto',
			'post_content'   => '',
			'post_status'    => 'inherit'
		], $file );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		wp_update_attachment_metadata(
			$image_id,
			wp_generate_attachment_metadata( $image_id, $file )
		);

		return $image_id;
	}

	/**
	 * Geeft zoekpatronen voor bestandsnamen terug
	 * - Continent, soort werk en land
	 * - Continent en soort werk
	 * - Continent en land
	 * - Continent
	 * 
	 * @return array
	 */
	public function get_patterns() {
	
		$country_slug = $this->country->get_slug();
		$continent_slug = $this->country->get_continent()->get_slug();

		$work_slugs = array_map(
			function( $work_type ) {
				return $work_type->get_slug();
			}, 
			$this->work_types
		);
		$work_slugs = implode( ',', $work_slugs );
		$extensions = implode( ',', $this->extensions );

		// $patterns = [
		// 	"{$continent_slug}/{{$work_slugs}}/{$country_slug}/*.{{$extensions}}",
		// 	"{$continent_slug}/*/{$country_slug}/*.{{$extensions}}",
		// 	"{$continent_slug}/{{$work_slugs}}/*/*.{{$extensions}}",
		// 	"{$continent_slug}/*/*/*.{{$extensions}}",
		// ];
		$patterns = [
			"{$continent_slug}/{{$work_slugs}}/{$country_slug}/*.{{$extensions}}", // europa/natuur/ijsland/*.jpg
			"{$continent_slug}/{{$work_slugs}}/*.{{$extensions}}", // europa/natuur/*.jpg
			"{$continent_slug}/*.{{$extensions}}", // europa/*.jpg
		];
		return $patterns;
	}

}