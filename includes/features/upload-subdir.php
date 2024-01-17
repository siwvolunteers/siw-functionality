<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Filter;
use SIW\Base;

class Upload_Subdir extends Base {

	#[Add_Filter( 'wp_handle_sideload_prefilter' )]
	#[Add_Filter( 'wp_handle_upload_prefilter' )]
	public function add_upload_subdir_filter( array $file ): array {
		add_filter( 'upload_dir', [ $this, 'set_upload_subdir' ] );
		return $file;
	}

	#[Add_Filter( 'wp_handle_upload' )]
	public function remove_upload_subdir_filter( array $fileinfo ): array {
		remove_filter( 'upload_dir', [ $this, 'set_upload_subdir' ] );
		return $fileinfo;
	}

	public function set_upload_subdir( array $path ): array {

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

		if ( is_string( $subdir ) ) {
			$subdir = '/' . $subdir;

			$path['path']    = str_replace( $path['subdir'], '', $path['path'] ); // TODO: wp_normalize_path
			$path['url']     = str_replace( $path['subdir'], '', $path['url'] );
			$path['subdir']  = $subdir;
			$path['path']   .= $subdir;
			$path['url']    .= $subdir;
		}

		return $path;
	}

	protected function get_post_type_subdir(): ?string {
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( is_null( $post_id ) ) {
			return null;
		}
		$post_type = get_post_type( $post_id );

		$cpt_upload_subdirs = [];
		/**
		 * Upload subdir
		 *
		 * @param array $cpt_upload_dirs
		 */
		$cpt_upload_subdirs = apply_filters( 'siw_cpt_upload_subdirs', $cpt_upload_subdirs );

		return $cpt_upload_subdirs[ $post_type ] ?? null;
	}

	protected function get_extension_subdir(): ?string {
		$name = isset( $_POST['name'] ) ? sanitize_file_name( wp_unslash( $_POST['name'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
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
