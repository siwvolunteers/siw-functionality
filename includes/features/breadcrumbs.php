<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Elements\Breadcrumbs as Breadcrumbs_Element;
use SIW\Util\CSS;

/**
 * Breadcrumbs
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Breadcrumbs extends Base {

	const ASSETS_HANDLE = 'siw-breadcrumbs';

	/** Array met crumbs */
	protected array $crumbs = [];

	/** Huidige pagina/post */
	protected string $current;

	#[Action( 'generate_after_navigation' ) ]
	/** Genereer breadcrumbs */
	public function generate_crumbs(): void {

		if ( is_front_page() ) {
			return;
		}

		$this->set_crumbs();
		$this->set_current();

		Breadcrumbs_Element::create()
			->add_items( $this->crumbs )
			->set_current( $this->current )
			->add_class( CSS::HIDE_ON_MOBILE_CLASS )
			->render();
	}

	/** Zet huidige pagina */
	protected function set_current() {
		if ( is_front_page() ) {
			$this->current = __( 'Home', 'siw' );
		} elseif ( is_single() || is_page() ) {
			$this->current = get_the_title();
		} elseif ( is_home() ) {
			$this->current = get_the_title( get_option( 'page_for_posts', true ) );
		} elseif ( is_post_type_archive() ) {
			$this->current = post_type_archive_title( '', false ); // TODO: filter
		} elseif ( is_tax() ) {
			// $this->current = get_the_archive_title(); //TODO: filter
			$this->current = single_term_title( '', false );
		} else {
			$this->current = '';
		}
	}

	/** Zet kruimels */
	protected function set_crumbs() {

		// Als het de homepage is zijn we snel klaar
		if ( is_front_page() ) {
			return;
		}

		// Anders beginnen we met home
		$this->add_crumb( __( 'Home', 'siw' ), home_url( '/' ) );

		if ( is_page() ) {
			$ancestors = get_ancestors( get_the_ID(), 'page' );
			$ancestors = array_reverse( $ancestors );
			if ( count( $ancestors ) > 0 ) {
				foreach ( $ancestors as $page_id ) {
					$this->add_crumb( get_the_title( $page_id ), get_permalink( $page_id ) );
				}
			}
		} elseif ( function_exists( 'is_product' ) && is_product() ) {
			$this->add_shop_crumb();
			$this->add_taxonomy_crumb( 'product_cat' );
		} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$this->add_shop_crumb();
		} elseif ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
			$this->add_shop_crumb(); // TODO: kan wel samen met de vorige toch?
		} elseif ( is_singular( 'post' ) ) {
			$this->add_crumb(
				get_the_title( get_option( 'page_for_posts', true ) ),
				get_permalink( get_option( 'page_for_posts', true ) )
			);
		} elseif ( is_single() ) {

			$post_type = get_post_type();
			$post_type_object = get_post_type_object( $post_type );
			$title = apply_filters( 'post_type_archive_title', $post_type_object->labels->name ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

			$this->add_crumb(
				$title,
				get_post_type_archive_link( $post_type )
			);

			// TODO: overige post types?
			if ( is_singular( 'siw_tm_country' ) ) {
				$this->add_taxonomy_crumb( 'siw_tm_country_continent' );
			}
		}
	}

	/** Voegt winkel crumb toe */
	protected function add_shop_crumb() {
		$shop_page_id = wc_get_page_id( 'shop' );
		$this->add_crumb( get_the_title( $shop_page_id ), get_permalink( $shop_page_id ) );
	}

	/** Voegt crumb voor taxonomy toe */
	protected function add_taxonomy_crumb( string $taxonomy ) {
		$terms = wp_get_post_terms(
			get_the_ID(),
			$taxonomy
		);
		if ( $terms && ! is_wp_error( $terms ) ) {
			if ( is_array( $terms ) ) {
				$this->add_crumb(
					$terms[0]->name,
					get_term_link( $terms[0], $taxonomy )
				);
			}
		}
	}

	/** Voegt kruimel toe aan pad */
	protected function add_crumb( string $title, string $url ) {
		$this->crumbs[] = [
			'title' => $title,
			'url'   => $url,
		];
	}
}
