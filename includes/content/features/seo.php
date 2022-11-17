<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Active_Posts as I_Active_Posts;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * SEO features
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class SEO extends Base {

	/** Init */
	protected function __construct( protected I_Type $type, protected I_Active_Posts $active_posts ) {}

	#[Filter( 'the_seo_framework_post_meta' )]
	/** Zet SEO-noindex */
	public function set_seo_noindex( array $meta, int $post_id ): array {
		if ( $this->type->get_post_type() === get_post_type( $post_id ) ) {
			$meta['_genesis_noindex'] = intval( ! $this->active_posts->is_post_active( $post_id ) );
		}
		return $meta;
	}

}
