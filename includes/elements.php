<?php declare(strict_types=1);

namespace SIW;

use SIW\Core\Template;
use SIW\Elements\List_Columns;
use SIW\Elements\Table;

/**
 * Functies om Elements te genereren
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Elements {

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
				return List_Columns::create()->add_items( $data )->generate();
			case 'table':
				return Table::create()->add_items( $data )->generate();
		}
	}
}
