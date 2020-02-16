<?php

namespace SIW\Elements;

use SIW\CSS;

/**
 * Carousel met posts
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Carousel {

	/**
	 * Versienummer 
	 */
	const FLICKITY_VERSION = '2.2.1';

	/**
	 * Post type
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Taxonomy voor query
	 *
	 * @var string
	 */
	protected $taxonomy;

	/**
	 * Term voor query
	 *
	 * @var string
	 */
	protected $term;

	/**
	 * Aantal items in carousel
	 *
	 * @var int
	 */
	protected $items = 6;

	/**
	 * Aantal kolommen in carousel
	 *
	 * @var int
	 */
	protected $columns = 4;

	/**
	 * Tekst voor knop
	 *
	 * @var string
	 */
	protected $button_text;

	/**
	 * Opties voor carousel
	 *
	 * @var array
	 */
	protected $options = [
		'cellAlign'  => 'left',
		'contain'    => true,
		'wrapAround' => true,
		'autoPlay'   => 2000,
		'pageDots'   => false,
	];

	/**
	 * Voegt stylesheet toe
	 */
	public function enqueue_styles() {
		wp_register_style( 'flickity', SIW_ASSETS_URL . 'modules/flickity/flickity.css', [], self::FLICKITY_VERSION );
		wp_enqueue_style( 'flickity' );
	}

	/**
	 * Voegt scripts toe
	 */
	public function enqueue_scripts() {
		wp_register_script( 'flickity', SIW_ASSETS_URL . 'modules/flickity/flickity.js', [], self::FLICKITY_VERSION, true );
		wp_enqueue_script( 'flickity' );
	}

	/**
	 * Haalt responsive classes op
	 * 
	 * @return string
	 */
	protected function get_responsive_class() {
		switch ( $this->columns ) {
			case 1:
				$column_size = 12;
				$tablet_size = 12;
				$mobile_size = 12;
				break;
			case 2:
				$column_size = 6;
				$tablet_size = 6;
				$mobile_size = 12;
				break;
			case 3:
				$column_size = 4;
				$tablet_size = 6;
				$mobile_size = 12;
				break;
			case 4:
				$column_size = 3;
				$tablet_size = 6;
				$mobile_size = 12;
				break;
			default:
				$column_size = 12;
				$tablet_size = 12;
				$mobile_size = 12;
		}
		$class = CSS::generate_responsive_class( $column_size, $tablet_size, $mobile_size );
		return $class;
	}


	/**
	 * Zet post type voor carousel
	 *
	 * @param string $post_type
	 */
	public function set_post_type( string $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Zet aantal items van carousel
	 *
	 * @param int $items
	 */
	public function set_items( int $items ) {
		$this->items = intval( $items );
	}

	/**
	 * Zet aantal kolommen van carousel
	 *
	 * @param int $columns
	 */
	public function set_columns( int $columns ) {
		$this->columns = intval( $columns );
	}

	/**
	 * Zet taxonomy en term voor carousel
	 *
	 * @param string $taxonomy
	 * @param string $term
	 */
	public function set_taxonomy_term( string $taxonomy, string $term ) {
		$this->taxonomy = $taxonomy;
		$this->term = $term;
	}

	/**
	 * Zet opties voor carousel
	 *
	 * @param array $options
	 */
	public function set_options( array $options ) {
		$this->options = wp_parse_args( $options, $this->options );
	}

	/**
	 * Genereert carousel
	 * 
	 * @return string
	 * 
	 * @todo leesbaarder maken
	 */
	public function render() {
		
		$this->enqueue_scripts();
		$this->enqueue_styles();

		$query = $this->generate_query();

		ob_start();
		?>
		<div class="siw-carousel">
		<?php
		if ( $query->have_posts() ) {
			?>
			<div class="main-carousel" data-flickity='<?php echo json_encode( $this->options );?>'>
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				?> <div class="<?php echo esc_attr( $this->get_responsive_class() );?> carousel-cell">
					<?php include( $this->get_template() );?>
				</div>
				<?php
			}
			echo '</div>';
		} else {
			//TODO: tekst bij geen posts?
		}

		echo '</div>';

		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * Genereert query
	 * 
	 * @return \WP_Query
	 */
	protected function generate_query() {
		$args = [
			'post_type'      => $this->post_type,
			'posts_per_page' => $this->items,
			'orderby'        => 'rand',
		];

		if ( isset( $this->taxonomy ) && isset( $this->term ) ) {
			$args['tax_query'] = [
				[
					'taxonomy'         => $this->taxonomy,
					'terms'            => $this->term,
					'field'            => 'slug',
				],
			];
		}
		return new \WP_Query( $args );
	}

	/**
	 * Haal templatebestand op voor post type
	 * 
	 * @return string
	 */
	protected function get_template() {
		//TODO:filter

		$templates = [
			'siw_tm_country' => SIW_TEMPLATES_DIR . '/content-tm_country.php',
		];

		$template = $templates[ $this->post_type ] ?? '';
		return $template;
	}
}
