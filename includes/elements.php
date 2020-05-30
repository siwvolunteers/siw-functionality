<?php

namespace SIW;

use SIW\HTML;
use SIW\Elements\Accordion;
use SIW\Elements\Tablist;
use SIW\Elements\Modal;
use SIW\Util\CSS;

/**
 * Functies om Elements te genereren
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @todo      render-functies maken
 */
class Elements {

	/**
	 * Genereer accordion
	 * 
	 * @param  array $panes
	 * 
	 * @return string
	 */
	public static function generate_accordion( array $panes ) {
		if ( empty( $panes) ) {
			return;
		}

		$accordion = new Accordion;
		foreach ( $panes as $pane ) {

			$pane = wp_parse_args(
				$pane,
				[ 
					'title'       => '',
					'content'     => '',
					'show_button' => false,
					'button_url'  => '',
					'button_text' => ''
				]
			);

			$accordion->add_pane(
				$pane['title'],
				$pane['content'],
				$pane['show_button'],
				$pane['button_url'],
				$pane['button_text']
			);
		}
		return $accordion->generate();
	}

	/**
	 * Genereer tablist
	 * 
	 * @param  array $panes
	 * 
	 * @return string
	 */
	public static function generate_tabs( array $panes ) {
		if ( empty( $panes) ) {
			return;
		}

		$tablist = new Tablist;
		foreach ( $panes as $pane ) {

			$pane = wp_parse_args(
				$pane,
				[ 
					'title'       => '',
					'content'     => '',
					'show_button' => false,
					'button_url'  => '',
					'button_text' => ''
				]
			);

			$tablist->add_pane(
				$pane['title'],
				$pane['content'],
				$pane['show_button'],
				$pane['button_url'],
				$pane['button_text']
			);
		}
		return $tablist->generate();
	}

	/**
	 * Genereert modal voor pagina
	 *
	 * @param int $page_id
	 * @param string $link_text
	 *
	 * @return string
	 */
	public static function generate_page_modal( int $page_id, string $link_text ) {
		$page_id = i18n::get_translated_page_id( $page_id );
		$page = get_post( $page_id );

		$modal = new Modal( "page-{$page_id}");
		$modal->set_title( $page->post_title );
		$modal->set_content( do_shortcode( $page->post_content ) );

		return $modal->generate_link( $link_text, get_permalink( $page ) );
	}

	/**
	 * Genereert modal
	 *
	 * @param string $title
	 * @param string $content
	 * @param string $link_text
	 *
	 * @return string
	 */
	public static function generate_modal( string $title, string $content, string $link_text ) {
		$modal = new Modal;
		$modal->set_title( $title );
		$modal->set_content( $content );

		return $modal->generate_link( $link_text );
	}

	/**
	 * Genereert html voor icon
	 *
	 * @param string $icon_class
	 * @param int $size
	 * @param string $background
	 * 
	 * @return string
	 */
	public static function generate_icon( string $icon_class, int $size = 2, string $background = 'none' ) {

		switch ( $background ) {
			case 'circle':
			case 'square':
				$has_background = true;
				$background_class = "siw-icon-{$background}";
				break;
			default:
			$has_background = false;
		}

		if ( $has_background ) {
			$background_icon = HTML::generate_tag(
				'svg',
				[
					'class' => 'siw-background-icon',
				],
				sprintf( '<use xlink:href="#%s" />', $background_class ),
				true
			);

			$icon = HTML::generate_tag(
				'svg',
				[
					'class' => 'siw-icon-inverse',
				],
				sprintf( '<use xlink:href="#%s" />', $icon_class ),
				true
			);
	
			return HTML::generate_tag(
				'span',
				[
					'class'       => sprintf( 'siw-icon siw-icon-background siw-icon-background-%sx', $size ),
					'aria-hidden' => 'true',
					'focusable'   => 'false'
				],
				$background_icon . $icon,
				true
			);
	
		}
		else {
			return HTML::generate_tag(
				'svg',
				[
					'class'       => "siw-icon siw-icon-{$size}x",
					'aria-hidden' => 'true',
					'focusable'   => 'false'
				],
				sprintf( '<use xlink:href="#%s" />', $icon_class ),
				true
			);
		}
	}

	/**
	 * Genereert quote
	 *
	 * @param string $quote
	 *
	 * @return string
	 */
	public static function generate_quote( string $quote ) {
		return HTML::generate_tag(
			'div',
			[ 'class' => 'siw-quote'],
			self::generate_icon( 'siw-icon-quote-left' ) . SPACE . esc_html( $quote ),
			true
		);
	}

	/**
	 * Genereert lijst of tabel met openingstijden
	 *
	 * @param string $type table|list
	 * 
	 * @return string
	 */
	public static function generate_opening_hours( string $type = 'table' ) {
		
		$opening_hours = siw_get_option( 'opening_hours' );
		$special_opening_hours = siw_get_option( 'special_opening_hours', [] );
		
		$days = siw_get_days();

		for ( $i = 0; $i <= 6; $i++ ) {
			$timestamp = strtotime( date( 'Y-m-d' ) . "+{$i} days" );
			$date = date( 'Y-m-d', $timestamp );
			$day = date( 'N', $timestamp );
	
			$opening_times = $opening_hours[ "day_{$day}" ];

			// Bepaal afwijkende openingstijden (indien van toepassing)
			if ( isset( $special_opening_hours[ $date ] ) ) {
				$special_opening_times = $special_opening_hours[ $date ];
				$opening_times = sprintf( '<del>%s</del> <ins>%s</ins>', $opening_times, $special_opening_times );
			}
		
			//Huidige dag bold maken TODO: netter
			$data[] = [
				( 0 == $i ) ? '<b>' . $days[ $day ] . '</b>' : $days[ $day ],
				( 0 == $i ) ? '<b>' . $opening_times . '</b>' : $opening_times,
			];
		}

		switch ( $type ) {
			case 'list':
				$callback = function( &$value, $key ) {
					$value = implode( ': ', $value );
				};
				array_walk( $data, $callback );
				return HTML::generate_list( $data );
			case 'table':
				return HTML::generate_table( $data );
		}
	}

	/**
	 * Haalt gegevens over interactieve kaarten op
	 *
	 * @return array
	 */
	public static function get_interactive_maps() : array {
		$maps = [
			[
				'id'    => 'nl',
				'name'  => __( 'Nederland', 'siw' ),
				'class' => 'Netherlands',
			],
			[
				'id'    => 'destinations',
				'name'  => __( 'Bestemmingen', 'siw' ),
				'class' => 'Destinations',
			],
			[
				'id'    => 'esc',
				'name'  => __( 'ESC', 'siw' ),
				'class' => 'ESC',
			],
		];
		return $maps;
	}

	/**
	 * Genereert interactieve kaart
	 *
	 * @param string $id
	 *
	 * @return string
	 */
	public static function generate_interactive_map( string $id ) : string {
		$maps = wp_list_pluck( self::get_interactive_maps(), 'class', 'id' );

		if ( ! isset( $maps[ $id ] ) ) {
			return null;
		}
		$class = "\SIW\Elements\Interactive_Map_{$maps[ $id ]}";
		$map = new $class;
		return $map->generate();
	}


	public static function generate_features( array $features, int $columns ) : string {
		$output = '<div class="grid-container siw-features">';
		foreach ( $features as $feature ) {
			$output .= self::generate_feature(
				$feature,
				[
					'class' => CSS::generate_responsive_classes( $columns ) . ' feature',
				]
			);
		}
		$output .= '</div>';
		return $output;
	}


	public static function generate_feature( array $feature, array $attributes ) : string {
		
		$feature = wp_parse_args(
			$feature,
			[
				'icon'     => '', //TODO: standaard icoon?
				'title'    => '',
				'content'  => '',
				'add_link' => false,
				'link_url' => '',
			]
		);

		ob_start();
		?>
		<div <?php HTML::render_attributes( $attributes);?>>
			<?php echo Elements::generate_icon( $feature['icon'], 4, 'circle' );?>
			<br>
			<h3><?php echo esc_html( $feature['title'] ); ?></h3>
			<?php echo wpautop( wp_kses_post( $feature['content'] ) );?>
			<?php 
			if ( $feature['add_link'] ) {
				echo HTML::generate_link( $feature['link_url'], __( 'Lees meer', 'siw' ), [ 'class' => 'button ghost'] );
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}

}
