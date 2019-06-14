<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class om upload directory te zetten op basis van content
 * 
 * @package     SIW
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Upload_Dir {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'wp_handle_upload_prefilter', [ $self, 'add_upload_dir_filter'] );
		add_filter( 'wp_handle_upload', [ $self, 'remove_upload_dir_filter'] );
	}

	/**
	 * Voegt filter toe
	 *
	 * @param array $file
	 * @return array
	 */
	public function add_upload_dir_filter( $file ) {
		add_filter( 'upload_dir', [ $this, 'set_upload_dir'] );
		return $file;
	}

	/**
	 * Verwijdert filter weer
	 *
	 * @param array $fileinfo
	 * @return array
	 */
	public function remove_upload_dir_filter($fileinfo){
		remove_filter('upload_dir', [ $this, 'set_upload_dir'] );
		return $fileinfo;
	}

	/**
	 * Bepaal upload dir
	 *
	 * @param array $path
	 * @return array
	 */
	public function set_upload_dir( $path ) {

		/* Afbreken bij een fout */
		if ( ! empty( $path['error'] ) ) {
			return $path;
		}

		$custom_dir = '';

		/* Bepaal extensie en post_type */
		$extension = pathinfo( $_POST['name'], PATHINFO_EXTENSION);
		$post_type = get_post_type( $_POST['post_id'] );

		$post_type_dir = $this->get_post_type_dir( $post_type );
		$extension_dir = $this->get_extension_dir( $extension );

		if ( ! empty( $extension_dir ) ) {
			$custom_dir = '/' . $extension_dir;
		}

		if ( ! empty( $post_type_dir ) ) {
			$custom_dir = '/' . $post_type_dir;
		}

		if ( ! empty( $custom_dir ) ) {
			$path['path']    = str_replace( $path['subdir'], '', $path['path'] );
			$path['url']     = str_replace( $path['subdir'], '', $path['url'] );
			$path['subdir']  = $custom_dir;
			$path['path']   .= $custom_dir;
			$path['url']    .= $custom_dir;
		}
		
		return $path;
	}

	/**
	 * Bepaal directory op basis van post type
	 *
	 * @param string $post_type
	 * @return string
	 */
	protected function get_post_type_dir( $post_type ) {
		switch ( $post_type ) {
			case 'siw_tm_country':
				$dir = 'op-maat';
				break;
			default:
				$dir = '';
		}

		return $dir;
	}

	/**
	 * Bepaal directory op basis van extensie
	 *
	 * @param string $extension
	 * @return string
	 */
	protected function get_extension_dir( $extension ) {
		switch( $extension ) {
			case 'pdf':
				$dir = 'pdf';
				break;
			default:
				$dir = '';
		}

		return $dir;
	}
}