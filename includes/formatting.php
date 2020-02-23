<?php

namespace SIW;

use SIW\Elements\Accordion;
use SIW\Elements\Tablist;
use SIW\Elements\Modal;

/**
 * Hulpfuncties t.b.v. formattering
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Formatting {

	/**
	 * Formatteert getal als percentage
	 * @param  float $percentage
	 * @param  int $decimals
	 * @return string
	 */
	public static function format_percentage( float $percentage, int $decimals = 0 ) {
		$percentage = number_format_i18n( $percentage, $decimals );
		return sprintf( '%s&nbsp;&percnt;', $percentage );
	}

	/**
	 * Formatteert getal als bedrag
	 *
	 * @param float $amount
	 * @param int $decimals
	 * @param string $currency_code
	 * @return string
	 * 
	 * @uses siw_get_currency()
	 */
	public static function format_amount( float $amount, int $decimals = 0, string $currency_code = 'EUR' ) {
		$currency = siw_get_currency( $currency_code );
		$currency_symbol = $currency->get_symbol();
		$amount = number_format_i18n( $amount, $decimals );
		return sprintf( '%s&nbsp;%s', $currency_symbol, $amount );
	}

	/**
	 * Formatteert kortingsbedrag
	 *
	 * @param float $amount
	 * @param float $sale_amount
	 * @param int $decimals
	 * @param string $currency_code
	 */
	public static function format_sale_amount( float $amount, float $sale_amount, int $decimals = 0, string $currency_code = 'EUR' ) {
		return sprintf(
			'<del>%s</del>&nbsp;<ins>%s</ins>',
			self::format_amount( $amount, $decimals, $currency_code ),
			self::format_amount( $sale_amount, $decimals, $currency_code )
		);
	}

	/**
	 * Genereert kolommen
	 *
	 * @param array $cells
	 * @return string
	 */
	public static function generate_columns( array $cells ) {
		$columns = '<div class="row">';
		foreach ( $cells as $cell ){
			$columns .= sprintf( '<div class="col-md-%s">%s</div>', $cell['width'], do_shortcode( $cell['content'] ) );
		}
		$columns .= '</div>';
		return $columns;
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
					'button_link' => '',
					'button_text' => ''
				]
			);

			$accordion->add_pane(
				$pane['title'],
				$pane['content'],
				$pane['show_button'],
				$pane['button_link'],
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
					'button_link' => '',
					'button_text' => ''
				]
			);

			$tablist->add_pane(
				$pane['title'],
				$pane['content']
			);
		}
		return $tablist->generate();
	}

	/**
	 * Parset template o.b.v. variabelen
	 *
	 * @param string $template
	 * @param array $vars
	 * @return string
	 */
	public static function parse_template( string $template, array $vars ) {
		$variables = [];
		foreach ( $vars as $key => $value ) {
			$variables[ '{{ ' . $key . ' }}' ] = $value;
		}
		return strtr( $template, $variables  );
	}

	/**
	 * Genereert script-tag met JSON-LD op basis van array
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public static function generate_json_ld( array $data ) {
		ob_start();
		?>
		<script type="application/ld+json">
		<?php echo json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);?>
		</script>
		<?php
		return ob_get_clean();
	}

	/**
	 * Formatteert datum als tekst
	 *
	 * @param string $date Y-m-d
	 * @param bool $year jaar toevoegen aan tekst
	 * @return string
	 */
	public static function format_date( $date, bool $year = true ) {
		$format = $year ? 'j F Y' : 'j F';
		return date_i18n( $format, strtotime( $date ) );
	}

	/**
	 * Formatteert datumrange als tekst
	 *
	 * @param string $date_start Y-m-d
	 * @param string $date_end Y-m-d
	 * @param bool $year jaar toevoegen aan tekst
	 *
	 * @return string
	 */
	public static function format_date_range( string $date_start, string $date_end, bool $year = true ) {
		
		if ( $date_start === $date_end ) {
			return self::format_date( $date_start, $year );
		}

		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		$format_end = $year ? 'j F Y' :  'j F';
		if ( $year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
			$format_start = 'j F Y';
		}
		elseif ( $date_start_array['month'] != $date_end_array['month'] ) {
			$format_start = 'j F';
		}
		else {
			$format_start = 'j';
		}

		return sprintf(
			__( '%s t/m %s', 'siw' ),
			date_i18n( $format_start, strtotime( $date_start ) ),
			date_i18n( $format_end, strtotime( $date_end ) )
		);
	}

	/**
	 * Formatteert maand uit datum als tekst
	 *
	 * @param string $date Y-m-d
	 * @param bool $year Jaar toevoegen aan tekst
	 *
	 * @return string
	 */
	public static function format_month( $date, $year = true ) {
		$format = $year ? 'F Y' :  'F';
		return date_i18n( $format, strtotime( $date ) );
	}

	/**
	 * Zet array van zinnen om naar tekst
	 *
	 * @param array $array
	 * 
	 * @return string
	 */
	public static function array_to_text( array $array, string $glue = SPACE ) {
		return implode( $glue, $array );
	}

	/**
	 * Formatteert maand-range uit datums als tekst
	 *
	 * @param string $date_start Y-m-d
	 * @param string $date_end Y-m-d
	 * @param bool $year jaar toevoegen aan tekst
	 *
	 * @return string
	 */
	public static function format_month_range( string $date_start, string $date_end, bool $year = true ) {

		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		if ( $date_start === $date_end || ( $date_start_array['month'] === $date_end_array['month'] && $date_start_array['year'] === $date_end_array['year'] ) ) {
			return self::format_month( $date_start, $year );
		}

		$format_end = $year ? 'F Y' :  'F';
		if ( $year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
			$format_start = 'F Y';
		}
		else {
			$format_start = 'F';
		}

		return sprintf(
			__( '%s t/m %s', 'siw' ),
			date_i18n( $format_start, strtotime( $date_start ) ),
			date_i18n( $format_end, strtotime( $date_end ) )
		);
	}

	/**
	 * Formatteert lokale bijdrage
	 *
	 * @param float $fee
	 * @param string $currency_code
	 * 
	 * @return string
	 */
	public static function format_local_fee( float $fee, string $currency_code ) {
		if ( 0 === $fee || ! is_string( $currency_code ) ) {
			return '';
		}
		$currency = siw_get_currency( $currency_code );
		if ( $currency && 'EUR' != $currency_code ) {
			$local_fee = sprintf( '%s %d (%s)', $currency->get_symbol(), $fee, $currency->get_name() );
		}
		elseif ( 'EUR' == $currency_code ) {
			$local_fee = sprintf( '&euro; %s', $fee );
		}
		else {
			$local_fee = sprintf( '%s %d', $currency_code, $fee );
		}
		return $local_fee;
	}

	/**
	 * Formatteert aantal vrijwilligers
	 *
	 * @param int $total
	 * @param int $male
	 * @param int $female
	 * 
	 * @return string
	 */
	public static function format_number_of_volunteers( int $total, int $male, int $female ) {

		$male_label = ( 1 == $male ) ? 'man' : 'mannen';
		$female_label = ( 1 == $female ) ? 'vrouw' : 'vrouwen';
	
		if ( $total == ( $male + $female ) ) {
			$number_of_volunteers = sprintf( '%d (%d %s en %d %s)', $total, $male, $male_label, $female, $female_label );
		}
		else {
			$number_of_volunteers = strval( $total );
		}
		return $number_of_volunteers;
	}

	/**
	 * Formatteert leeftijdsrange
	 *
	 * @param int $min_age
	 * @param int $max_age
	 * 
	 * @return string
	 */
	public static function format_age_range( int $min_age, int $max_age ) {
		if ( $min_age < 1 ) {
			$min_age = 18;
		}
		if ( $max_age < 1 ) {
			$max_age = 99;
		}
		return sprintf( '%d t/m %d jaar', $min_age, $max_age );
	}

	/**
	 * Formatteert openingstijden als lijst of tabel
	 *
	 * @param array $opening_hours
	 * @param array $special_opening_hours
	 * @param string $type table|list
	 * 
	 * @return string
	 */
	public static function format_opening_hours( array $opening_hours, array $special_opening_hours = [], string $type = 'table' ) {
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

		$modal = new Modal;
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

}
