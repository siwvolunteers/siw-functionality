<?php declare(strict_types=1);

namespace SIW;

/**
 * Class om upload subdirectory te zetten op basis van content en bestandstype
 *
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @since       3.0.0
 */
class Upload_Subdir {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'wp_handle_sideload_prefilter', [ $self, 'add_upload_subdir_filter'] );
		add_filter( 'wp_handle_upload_prefilter', [ $self, 'add_upload_subdir_filter'] );
		add_filter( 'wp_handle_upload', [ $self, 'remove_upload_subdir_filter'] );
	}

	/** Voegt filter toe */
	public function add_upload_subdir_filter( array $file ) : array {
		add_filter( 'upload_dir', [ $this, 'set_upload_subdir'] );
		return $file;
	}

	/** Verwijdert filter weer */
	public function remove_upload_subdir_filter( array $fileinfo ) : array {
		remove_filter( 'upload_dir', [ $this, 'set_upload_subdir'] );
		return $fileinfo;
	}

	/** Bepaal upload dir */
	public function set_upload_subdir( array $path ) : array {

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
		$subdir =
			$this->get_extension_subdir() ??
			$this->get_post_type_subdir() ??
			null;

		/**
		 * Custom upload directory
		 *
		 * @param string $custom_dir
		 */
		$subdir = apply_filters( 'siw_upload_subdir', $subdir );

		// Als er een
		if ( is_string( $subdir ) ) {
			$subdir = '/'. $subdir;

			$path['path']    = str_replace( $path['subdir'], '', $path['path'] ); //TODO: wp_normalize_path
			$path['url']     = str_replace( $path['subdir'], '', $path['url'] );
			$path['subdir']  = $subdir;
			$path['path']   .= $subdir;
			$path['url']    .= $subdir;
		}

		return $path;
	}

	/** Bepaal subdirectory op basis van post type */
	protected function get_post_type_subdir() : ?string {
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : null;
		if ( is_null( $post_id ) ) {
			return null;
		}
		$post_type = get_post_type( $post_id );

		$cpt_upload_subdirs = [];
		/**
		 * Upload subdir
		 * @param array $cpt_upload_dirs
		 */
		$cpt_upload_subdirs = apply_filters( 'siw_cpt_upload_subdirs', $cpt_upload_subdirs );

		return $cpt_upload_subdirs[ $post_type ] ?? null;
	}

	/** Bepaal subdirectory op basis van extensie */
	protected function get_extension_subdir() : ?string {
		$name = isset( $_POST['name'] ) ? sanitize_file_name( $_POST['name'] ): null;
		if ( is_null( $name ) ) {
			return null;
		}
		$extension = pathinfo( $name, PATHINFO_EXTENSION );

		$extension_subdirs = [
			'pdf' => 'documenten',
		];
		return $extension_subdirs[ $extension ] ?? null;
	}
}
