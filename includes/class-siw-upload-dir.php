<?php

/**
 * Class om upload directory te zetten op basis van content en bestandstype
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
		add_filter( 'wp_handle_sideload_prefilter', [ $self, 'add_upload_dir_filter'] );
		add_filter( 'wp_handle_upload_prefilter', [ $self, 'add_upload_dir_filter'] );
		add_filter( 'wp_handle_upload', [ $self, 'remove_upload_dir_filter'] );
	}

	/**
	 * Voegt filter toe
	 *
	 * @param array $file
	 * @return array
	 */
	public function add_upload_dir_filter( array $file ) {
		add_filter( 'upload_dir', [ $this, 'set_upload_dir'] );
		return $file;
	}

	/**
	 * Verwijdert filter weer
	 *
	 * @param array $fileinfo
	 * @return array
	 */
	public function remove_upload_dir_filter( array $fileinfo ) {
		remove_filter( 'upload_dir', [ $this, 'set_upload_dir'] );
		return $fileinfo;
	}

	/**
	 * Bepaal upload dir
	 *
	 * @param array $path
	 * @return array
	 */
	public function set_upload_dir( array $path ) {

		/* Afbreken bij een fout */
		if ( ! empty( $path['error'] ) ) {
			return $path;
		}

		/**
		 * Zet upload dir op basis van:
		 * 
		 * - Extensie
		 * - Post type
		 */
		$upload_dir =
			$this->get_extension_dir() ??
			$this->get_post_type_dir() ??
			null;

		/**
		 * Custom upload directory
		 *
		 * @param string $custom_dir
		 */
		$upload_dir = apply_filters( 'siw_upload_dir', $upload_dir );

		if ( null !== $upload_dir ) {
			$upload_dir = '/'. $upload_dir;

			$path['path']    = str_replace( $path['subdir'], '', $path['path'] );
			$path['url']     = str_replace( $path['subdir'], '', $path['url'] );
			$path['subdir']  = $upload_dir;
			$path['path']   .= $upload_dir;
			$path['url']    .= $upload_dir;
		}
		
		return $path;
	}

	/**
	 * Bepaal directory op basis van post type
	 *
	 * @return string
	 */
	protected function get_post_type_dir() {
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ): null;
		if ( null == $post_id ) {
			return null;
		}
		$post_type = get_post_type( $post_id );

		$cpt_upload_dirs = [
			'siw_tm_country' => 'op-maat',
			'siw_tm_story'   => 'ervaringen',
		];

		/**
		 * Upload directory voor CPT's
		 *
		 * @param array $checkout_fields
		 */
		$cpt_upload_dirs = apply_filters( 'siw_cpt_upload_dirs', $cpt_upload_dirs );

		return $cpt_upload_dirs[ $post_type ] ?? null;
	}

	/**
	 * Bepaal directory op basis van extensie
	 *
	 * @return string
	 */
	protected function get_extension_dir() {
		$name = isset( $_POST['name'] ) ? sanitize_title( $_POST['name'] ): null;
		if ( null == $name ) {
			return null;
		}
		$extension = pathinfo( $name, PATHINFO_EXTENSION );

		$extension_dirs = [
			'pdf' => 'documenten',
		];
		return $extension_dirs[ $extension ] ?? null;
	}
}
