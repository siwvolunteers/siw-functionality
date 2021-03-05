<?php declare(strict_types=1);

namespace SIW;

use SIW\Core\Template;
use SIW\Elements\Modal;

/**
 * Functies om Elements te genereren
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Elements {

	/** Genereert modal voor pagina */
	public static function generate_page_modal( int $page_id, string $link_text ) : string {
		$page_id = i18n::get_translated_page_id( $page_id );
		$page = get_post( $page_id );

		$modal = new Modal( "page-{$page_id}");
		$modal->set_title( $page->post_title );
		$modal->set_content( do_shortcode( $page->post_content ) );

		return $modal->generate_link( $link_text, get_permalink( $page ) );
	}

	/** Genereert modal */
	public static function generate_modal( string $title, string $content, string $link_text ) : string {
		$modal = new Modal;
		$modal->set_title( $title );
		$modal->set_content( $content );

		return $modal->generate_link( $link_text );
	}

	/** Genereert html voor icon TODO: aparte templates voor met en zonder background */
	public static function generate_icon( string $icon_class, int $size = 2, string $background = 'none' ) : string {

		switch ( $background ) {
			case 'circle':
			case 'square':
				$has_background = true;
				$background_class = $background;
				break;
			default:
				$has_background = false;
				$background_class = '';
		}

		return  Template::parse_template(
			'elements/icon',
			[
				'icon' => [
					'size'             => $size,
					'icon_class'       => $icon_class,
					'has_background'   => $has_background,
					'background_class' => $background_class,
				],
			]
		);
	}

	/** Genereert quote */
	public static function generate_quote( string $quote ) : string {
		return Template::parse_template(
			'elements/quote',
			[
				'icon' => [
					'size'       => 2,
					'icon_class' => 'siw-icon-quote-left',
				],
				'quote' => $quote
			]
		);
	}

	/**
	 * Genereert lijst of tabel met openingstijden
	 *
	 * @param string $type table|list
	 */
	public static function generate_opening_hours( string $type = 'table' ) : string {
		
		//Ophalen openingstijden
		$opening_hours = siw_get_option( 'opening_hours' );
		
		$opening_hours = array_map(
			fn( array $value ) : string => $value['open'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' ),
			array_filter( $opening_hours )
		);

		//Ophalen afwijkende openingstijden
		$special_opening_hours = siw_get_option( 'special_opening_hours', [] );

		$special_opening_hours = array_map(
			fn( array $value ) : string => $value['opened'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' ),
			array_column( $special_opening_hours , null, 'date' )
		);
		
		$days = siw_get_days();
		for ( $i = 0; $i <= 6; $i++ ) {
			$timestamp = strtotime( date( 'Y-m-d' ) . "+{$i} days" );
			$date = date( 'Y-m-d', $timestamp );
			$day = date( 'N', $timestamp );
	
			$opening_times = $opening_hours[ "day_{$day}" ];

			// Bepaal afwijkende openingstijden (indien van toepassing)
			if ( isset( $special_opening_hours[ $date ] ) ) {
				$opening_times = sprintf( '<del>%s</del> <ins>%s</ins>', $opening_times, $special_opening_hours[ $date ] );
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
				return self::generate_list( $data );
			case 'table':
				return self::generate_table( $data );
		}
	}

	/** Haalt gegevens over interactieve kaarten op */
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

	/** Genereert interactieve kaart */
	public static function generate_interactive_map( string $id ) : string {
		$maps = wp_list_pluck( self::get_interactive_maps(), 'class', 'id' );

		if ( ! isset( $maps[ $id ] ) ) {
			return null;
		}
		$class = "\SIW\Elements\Interactive_Maps\\{$maps[ $id ]}";
		$map = new $class;
		return $map->generate();
	}

	/** Genereert tabel */
	public static function generate_table( array $rows, array $headers = [] ) : string {
		return Template::parse_template(
			'elements/table',
			[
				'rows'    => $rows,
				'headers' => $headers,
			]
		);
	}

	/** Genereert lijst o.b.v. array met items */
	public static function generate_list( array $items, int $columns = 1 ) : string {
		return Template::parse_template(
			'elements/list',
			[
				'items'   => $items,
				'columns' => $columns,
			]
		);
	}
}
