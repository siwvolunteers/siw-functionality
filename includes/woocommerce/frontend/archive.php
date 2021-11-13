<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

/**
 * Aanpassingen aan overzichtspagina van groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Archive {

	/** Init */
	public static function init() {
		$self = new self();

		add_action( 'woocommerce_after_shop_loop_item_title', [ $self, 'show_dates'] );
		add_action( 'woocommerce_after_shop_loop_item', [ $self, 'show_project_code'], 1 );

		add_filter( 'the_seo_framework_the_archive_title', [ $self, 'set_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_generated_archive_excerpt', [ $self, 'set_seo_description' ], 10, 2 );

		add_filter( 'woocommerce_default_catalog_orderby_options', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_catalog_orderby', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_get_catalog_ordering_args', [ $self, 'process_catalog_ordering_args' ], 10, 3 );

		add_action( 'woocommerce_before_shop_loop_item_title', [ $self, 'show_featured_badge' ], 10 );
	}

	/** Toont datums */
	public function show_dates() {
		global $product;
		$duration = siw_format_date_range( $product->get_attribute('startdatum'), $product->get_attribute('einddatum'), false );
		echo wpautop( esc_html( $duration ) );
	}

	/** Toont projectcode */
	public function show_project_code() {
		global $product;
		echo '<hr>';
		echo '<span class="project-code">' . esc_html( $product->get_sku() ) . '</span>';
	}

	/** Past de SEO titel aan */
	public function set_seo_title( string $title, $term ): string {

		if ( ! is_a( $term, \WP_Term::class ) ) {
			return $title;
		}

		switch ( $term->taxonomy ) {
			case 'pa_land':
			case 'product_cat':
				$title = sprintf( __( 'Groepsprojecten in %s', 'siw' ), $term->name );
				break;
			case 'pa_doelgroep':
				$title = sprintf( __( 'Groepsprojecten voor %s', 'siw' ), $term->name );
				break;
			case 'pa_taal':
				$title = sprintf( __( 'Groepsprojecten met voertaal %s', 'siw' ), $term->name );
				break;
			case 'pa_soort-werk':
				$title = sprintf( __( 'Groepsprojecten met werk gericht op %s', 'siw' ), strtolower( $term->name ) );
				break;
			case 'pa_sdg':
				$title = sprintf( __( 'Groepsprojecten met werk gericht op het SDG %s', 'siw' ), strtolower( $term->name ) );
				break;
			case 'pa_maand':
				$title = sprintf( __( 'Groepsprojecten in de maand %s', 'siw' ), $term->name );
				break;
		}
		return $title;
	}

	/**
	 * Past SEO-beschrijving aan */
	public function set_seo_description( string $description, $term ): string {
		if ( ! is_a( $term, \WP_Term::class ) ) {
			return $description;
		}
		
		switch ( $term->taxonomy ) {
			case 'pa_land':
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen in %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case 'pa_soort-werk':
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), strtolower( $term->name ) ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case 'pa_sdg':
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op het Sustainable Development Goal %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
		}

		return $description;
	}

	/** Voegt extra sorteeroptie (startdatum) toe voor archive */
	public function add_catalog_orderby_options( array $options ): array {
		unset( $options['menu_order'] );
		unset( $options['popularity'] );
		unset( $options['rating'] );
		unset( $options['date'] );
		unset( $options['price'] );
		unset( $options['price-desc'] );
		$options['startdate'] = __( 'Startdatum', 'siw' );
		return $options;
	}

	/** Verwerkt extra sorteeroptie voor archive */
	public function process_catalog_ordering_args( array $args, string $orderby, string $order ): array {
		if ( 'startdate' == $orderby ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = 'start_date';
		}
		return $args;
	}

	/** Toont badge voor aanbevolen projecten */
	public function show_featured_badge() {
		global $product;
		if ( ! \is_shop() && ! \is_product_category() && ! \is_product_taxonomy() ) {
			return;
		}
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}
}
