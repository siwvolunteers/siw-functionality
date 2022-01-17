<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Util\CSS;

/**
 * Carousel met posts
 * 
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Carousel {

	/** Versienummer */
	const FLICKITY_VERSION = '2.3.0';

	const SCRIPT_HANDLE = 'flickity';

	const STYLE_HANDLE = 'flickity';

	/** Post type */
	protected string $post_type;

	/** Tax-query */
	protected array $tax_query;

	/** Meta query */
	protected array $meta_query = [];

	/** Aantal items in carousel */
	protected int $items = 6;

	/** Aantal kolommen in carousel */
	protected int $columns = 4;

	/** Tekst voor knop */
	protected string $button_text;

	/** Opties voor carousel */
	protected array $options = [
		'cellAlign'  => 'left',
		'contain'    => true,
		'wrapAround' => true,
		'autoPlay'   => 2000,
		'pageDots'   => false, //TODO: styling
	];

	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( self::STYLE_HANDLE, SIW_ASSETS_URL . 'vendor/flickity/flickity.css', [], self::FLICKITY_VERSION );
		wp_enqueue_style( self::STYLE_HANDLE );
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'vendor/flickity/flickity.pkgd.js', [], self::FLICKITY_VERSION, true );
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	/** Haalt responsive classes op */
	protected function get_responsive_classes() : string {

		$desktop_columns = $this->columns;
		$mobile_columns = 1;

		switch ( $this->columns ) {
			case 1:
				$tablet_columns = 1;
				break;
			case 2:
				$tablet_columns = 2;
				break;
			case 3:
				$tablet_columns = 2;
				break;
			case 4:
				$tablet_columns = 2;
				break;
			default:
				$tablet_columns = 1;
			}
		return CSS::generate_responsive_classes( $desktop_columns, $tablet_columns, $mobile_columns );
	}

	/** Zet post type voor carousel */
	public function set_post_type( string $post_type ) {
		$this->post_type = $post_type;
	}

	/** Zet aantal items van carousel */
	public function set_items( int $items ) {
		$this->items = $items;
	}

	/** Zet aantal kolommen van carousel */
	public function set_columns( int $columns ) {
		$this->columns = $columns;
	}

	/** Voegt tax query toe*/
	public function add_tax_query( array $tax_query ) {
		$this->tax_query[] = $tax_query;
	}

	/** Voeg meta query toe */
	public function add_meta_query( array $meta_query ) {
		$this->meta_query[] = $meta_query;
	}

	/** Zet opties voor carousel */
	public function set_options( array $options ) {
		$this->options = wp_parse_args( $options, $this->options );
	}

	/**
	 * Genereert carousel

	 * 
	 * @todo leesbaarder maken
	 */
	public function render(): string {
		
		$this->enqueue_scripts();
		$this->enqueue_styles();

		$query = $this->generate_query();

		ob_start();
		?>
		<div class="siw-carousel">
		<?php
		if ( $query->have_posts() ) {
			?>
			<div class="main-carousel grid-container" data-flickity='<?php echo json_encode( $this->options );?>'>
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				?>
				<div class="<?php echo esc_attr( $this->get_responsive_classes() );?> carousel-cell">
					<?php include( $this->get_template() );?>
				</div>
				<?php
			}
			echo '</div>';
		} else {
			//TODO: tekst bij geen posts? -> Instelling
		}
		echo '</div>';

		wp_reset_postdata();
		return ob_get_clean();
	}

	/** Genereert query */
	protected function generate_query(): \WP_Query {
		$args = [
			'post_type'      => $this->post_type,
			'posts_per_page' => $this->items,
			'orderby'        => 'rand',
		];

		if ( isset( $this->tax_query ) ) {
			$args['tax_query'] = $this->tax_query;
		}

		if ( ! empty( $this->meta_query ) ) {
			$args['meta_query'] = $this->meta_query;
		}

		//In het geval van Groepsprojecten alleen zichtbare projecten tonen
		if ( 'product' == $this->post_type ) {
			$args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'terms'    => [ 'exclude-from-search', 'exclude-from-catalog'],
				'field'    => 'slug',
				'operator' => 'NOT IN'
			];
		}
		return new \WP_Query( $args );
	}

	/**
	 * Haal templatebestand op voor post type
	 * @todo fallback-bestand
	 */
	protected function get_template() : string {
		$templates = apply_filters( 'siw_carousel_post_type_templates', [] );
		return $templates[ $this->post_type ] ?? '';
	}
}
