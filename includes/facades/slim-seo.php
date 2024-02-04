<?php declare(strict_types=1);

namespace SIW\Facades;

class Slim_SEO {

	public static function set_noindex( int $post_id, bool $noindex ) {
		$seo_data = self::get_seo_data( $post_id );
		$seo_data['noindex'] = (int) $noindex;
		self::set_seo_data( $post_id, $seo_data );
	}

	public static function get_noindex( int $post_id ): bool {
		$seo_data = self::get_seo_data( $post_id );
		return (bool) ( $seo_data['noindex'] ?? false );
	}

	protected static function get_seo_data( int $post_id ): array {
		$seo_data = get_post_meta( $post_id, 'slim_seo', true );
		if ( ! is_array( $seo_data ) ) {
			$seo_data = [];
		}
		return $seo_data;
	}

	protected static function set_seo_data( int $post_id, array $seo_data ) {
		update_post_meta( $post_id, 'slim_seo', $seo_data );
	}
}
