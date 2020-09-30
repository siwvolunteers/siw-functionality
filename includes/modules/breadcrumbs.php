<?php declare(strict_types=1);

namespace SIW\Modules;

/**
 * Verversen van de cache
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.6
 */
class Breadcrumbs {

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $crumbs = [];

	/**
	 * Huidige pagina/post
	 *
	 * @var string
	 */
	protected $current;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_shortcode( 'siw_breadcrumbs', [ $self, 'generate_crumbs'] );
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles'] );
	}


	public function enqueue_styles() {
		wp_register_style( 'siw-breadcrumbs', SIW_ASSETS_URL . 'css/modules/siw-breadcrumbs.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-breadcrumbs' );
	}


	public function generate_crumbs() : string {
		$this->set_crumbs();
		$this->set_current();

		$output = '<nav aria-label="Breadcrumb" class="breadcrumb">';
		$output .= '<ol class="breadcrumb">';

		foreach ( $this->crumbs as $crumb ) {
			$output .= sprintf( '<li class="breadcrumb-item"><a href="%s">%s</a></li>', $crumb['url'], $crumb['title'] );
		}
		$output .= sprintf( '<li class="breadcrumb-item">%s</li>', $this->current );
		$output .= '</ol>';
		$output .= '</nav>';
		return $output;
	}

	/**
	 * Zet huidige pagina
	 */
	protected function set_current() {
		if ( is_front_page() ) {
			$this->current = __( 'Home', 'siw' );
		}
		elseif ( is_single() || is_page() ) {
			$this->current = get_the_title();
		}
		elseif ( is_post_type_archive() ) {
			$this->current = post_type_archive_title( '', false); //TODO: filter
		}
		elseif ( is_archive() ) {
			//$this->current = get_the_archive_title(); //TODO: filter
			$this->current = single_term_title( '', false );
		}
	}

	/**
	 * Undocumented function
	 */
	protected function set_crumbs() {
		
		//Als het de homepage is zijn we snel klaar
		if ( is_front_page() ) {
			return;
		}
		
		//Anders beginnen we met home
		$this->add_crumb( __( 'Home', 'siw' ), home_url('/') );

		//
		if ( is_page() ) {
			$ancestors = get_ancestors( get_the_ID(), 'page');
			$ancestors = array_reverse( $ancestors );
			if ( count( $ancestors ) > 0 ) {
				foreach ( $ancestors as $page_id) {
					$this->add_crumb( get_the_title( $page_id ), get_permalink ( $page_id));
				}
			}
		}
		elseif ( function_exists( 'is_product' ) && is_product() ) {
			$this->add_shop_crumb();
			$this->add_taxonomy_crumb( 'product_cat');
		}
		elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$this->add_shop_crumb();
		}
		elseif ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
			$this->add_shop_crumb(); //TODO: kan wel samen met de vorige toch?
		}
		elseif ( is_single() ) {
			$post_type = get_post_type();
			$post_type_object= get_post_type_object( $post_type );
			$title = apply_filters( 'post_type_archive_title', $post_type_object->labels->name );

			$this->add_crumb(
				$title,
				get_post_type_archive_link( $post_type )
			);
			//get_archive
			//get_taxonomy
		}
	}

	/**
	 * Undocumented function
	 */
	protected function add_shop_crumb() {
		$shop_page_id = wc_get_page_id( 'shop' );
		$this->add_crumb( get_the_title( $shop_page_id ), get_permalink( $shop_page_id ) );
	}

	/**
	 * Undocumented function
	 *
	 * @param string $taxonomy
	 */
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

	/**
	 * Voegt kruimel toe aan pad
	 *
	 * @param string $title
	 * @param string $url
	 */
	protected function add_crumb( string $title, string $url ) {
		$this->crumbs[] = [
			'title' => $title,
			'url'   => $url
		];
	}
}
