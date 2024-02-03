<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\Content\Post\Event as Event_Post;
use SIW\Content\Post\Job_Posting as Job_Posting_Post;
use SIW\Content\Post\Story as Story_Post;
use SIW\Content\Post\TM_Country as TM_Country_Post;
use SIW\Content\Post_Types\Event;
use SIW\Content\Post_Types\Job_Posting;
use SIW\Content\Post_Types\Story;
use SIW\Content\Post_Types\TM_Country;
use SIW\Facades\WooCommerce;
use SIW\WooCommerce\Product\WC_Product_Project;
use SIW\WooCommerce\Taxonomy_Attribute;

class Carousel {

	public static function post_to_carousel_slide( \WP_Post $post ): array {

		switch ( $post->post_type ) {
			case TM_Country::get_post_type():
				$custom_post = new TM_Country_Post( $post );
				break;
			case Story::get_post_type():
				$custom_post = new Story_Post( $post );
				break;
			case Event::get_post_type():
				$custom_post = new Event_Post( $post );
				break;

			case Job_Posting::get_post_type():
				$custom_post = new Job_Posting_Post( $post );
				break;
		}

		return [
			'image'   => wp_get_attachment_image( $custom_post->get_thumbnail_id(), 'large' ),
			'title'   => $custom_post->get_title(),
			'excerpt' => $custom_post->get_excerpt(),
			'link'    => [
				'text' => __( 'Lees meer', 'siw' ),
				'url'  => $custom_post->get_permalink(),
			],
		];
	}

	public static function product_to_carousel_slide( WC_Product_Project $product ): array {

		$excerpt = sprintf(
			'%s<br/>%s<br/>%s',
			$product->get_country()->label(),
			implode( ' | ', WooCommerce::get_product_terms( $product->get_id(), Taxonomy_Attribute::WORK_TYPE->value, [ 'fields' => 'names' ] ) ),
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
