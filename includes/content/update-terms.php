<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Active_Posts as I_Active_Posts;
use SIW\Interfaces\Content\Taxonomies as I_Taxonomies;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Bijwerken van terms
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Update_Terms extends Base {

	/** Init */
	protected function __construct( protected I_Type $type, protected I_Taxonomies $taxonomies, protected I_Active_Posts $active_posts ) {}

	#[Filter( 'siw/update_terms/taxonomies' )]
	/** Zet taxonomiën om bij te werken via batch */
	public function set_update_terms_taxonomies( array $taxonomies ) : array {
		foreach ( array_keys( $this->taxonomies->get_taxonomies() ) as $taxonomy ) {
			$taxonomies[] = "siw_{$this->type->get_post_type()}_{$taxonomy}";
		}
		return $taxonomies;
	}

	#[Filter( 'siw/update_terms/meta_query' )]
	/** Zet meta query voor update van terms*/
	public function set_update_terms_meta_query( array $meta_query, string $term_taxonomy ) : array {
		foreach ( array_keys( $this->taxonomies->get_taxonomies() ) as $taxonomy ) {
			if ( "siw_{$this->type->get_post_type()}_{$taxonomy}" === $term_taxonomy ) {
				$meta_query = [ $this->active_posts->get_active_posts_meta_query() ];
				break;
			}
		}

		return $meta_query;
	}

}
