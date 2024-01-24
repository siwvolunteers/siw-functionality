<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\WooCommerce\Product\WC_Product_Project;
use SIW\WooCommerce\Taxonomy_Attribute;

class Carousel {

	public static function post_to_carousel_slide( \WP_Post $post ): array {

		switch ( $post->post_type ) {
			case 'siw_tm_country':
				$images = siw_meta( 'image', [ 'limit' => 1 ], $post->ID );
				$image = reset( $images );

				$slide = [
					'image'   => wp_get_attachment_image( $image['ID'], 'large' ),
					'title'   => get_the_title( $post ),
					'excerpt' => siw_meta( 'quote', [], $post->ID ),
					'link'    => [
						'text' => __( 'Bekijk land', 'siw' ),
						'url'  => get_the_permalink( $post ),
					],
				];
				break;
			default:
				$slide = [
					'image'   => get_the_post_thumbnail( $post, 'large' ),
					'title'   => get_the_title( $post ),
					'excerpt' => get_the_excerpt( $post ),
					'link'    => [
						'text' => __( 'Lees meer', 'siw' ),
						'url'  => get_the_permalink( $post ),
					],
				];
		}

		return $slide;
	}

	public static function product_to_carousel_slide( WC_Product_Project $product ): array {

		$excerpt = sprintf(
			'%s<br/>%s<br/>%s',
			$product->get_country()->label(),
			implode( ' | ', wc_get_product_terms( $product->get_id(), Taxonomy_Attribute::WORK_TYPE->value, [ 'fields' => 'names' ] ) ),
			siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false )
		);

		return [
			'image'   => $product->get_image(),
			'title'   => $product->get_name(),
			'excerpt' => $excerpt,
			'link'    => [
				'text' => __( 'Bekijk project', 'siw' ),
				'url'  => $product->get_permalink(),
			],
		];
	}
}
