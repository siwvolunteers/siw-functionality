<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Assets\Flickity;
use SIW\Util\CSS;

/**
 * Carousel met posts
 * 
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Carousel extends Element {

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

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'carousel';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'content' => $this->generate_content(),
		];
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		wp_enqueue_style( Flickity::ASSETS_HANDLE );
	}

	/** {@inheritDoc} */
	public function enqueue_scripts() {
		wp_enqueue_script( Flickity::ASSETS_HANDLE );
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
		return $this;
	}

	/** Zet aantal items van carousel */
	public function set_items( int $items ) {
		$this->items = $items;
		return $this;
	}

	/** Zet aantal kolommen van carousel */
	public function set_columns( int $columns ) {
		$this->columns = $columns;
		return $this;
	}

	/** Voegt tax query toe*/
	public function add_tax_query( array $tax_query ) {
		$this->tax_query[] = $tax_query;
		return $this;
	}

	/** Voeg meta query toe */
	public function add_meta_query( array $meta_query ) {
		$this->meta_query[] = $meta_query;
		return $this;
	}

	/** Zet opties voor carousel */
	public function set_options( array $options ) {
		$this->options = wp_parse_args( $options, $this->options );
		return $this;
	}

	/**
	 * Genereert carousel

	 * 
	 * @todo leesbaarder maken
	 */
	public function generate_content(): string {
		
		$query = $this->generate_query();

		ob_start();
		?>

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
