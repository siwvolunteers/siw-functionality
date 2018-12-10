<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hulpfuncties t.b.v. formattering
 *
 * @package SIW\Formatting
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Formatting {

	/**
	 * Formatteert getal als percentage
	 * @param  float  $percentage
	 * @param  integer $decimals
	 * @return string
	 */
	public static function format_percentage( $percentage, $decimals = 0 ) {
		$percentage = number_format( $percentage, $decimals );
		return sprintf( '%s&nbsp;&percnt;', $percentage );
	}

	/**
	 * Formatteert getal als bedrag
	 *
	 * @param float $amount
	 * @param integer $decimals
	 * @param string $currency
	 * @return string
	 * 
	 * @uses siw_get_currency()
	 */
	public static function format_amount( $amount, $decimals = 0, $currency_code = 'EUR' ) {
		$currency = siw_get_currency( $currency_code );
		$currency_symbol = $currency->get_symbol();
		$amount = number_format( $amount, $decimals, ',', '.' );
		return sprintf( '%s&nbsp;%s', $currency_symbol, $amount );
	}

	/**
	 * Genereert een `<ol>` of `<ul>` lijst van array
	 * @param array $items
	 * @param bool $ordered
	 *
	 * @return string
	 */
	public static function generate_list( $items, $ordered = false ) {
		if ( empty ( $items ) ) {
			return false;
		}
		$tag = $ordered ? 'ol' : 'ul';

		$list = "<{$tag}>";
		foreach ( $items as $item ) {
			$list .= '<li>' . (string) $item . '</li>'; //TODO: escaping
		}
		$list .= "</{$tag}>";

		return $list;
	}

	/**
	 * Genereert link
	 *
	 * @todo attributes, target en rel
	 *
	 * @param text $url
	 * @param text $text
	 * @return void
	 */
	public static function generate_link( $url, $text = false, $class = '' ) {

		if ( false == $text ) {
			$text = $url;
		}

		$link = sprintf( '<a class="%s" href="%s">%s</a>', self::sanitize_html_classes( $class ), esc_url( $url ), esc_html( $text ) );

		return $link;
	}

	/**
	 * Genereert externe link
	 *
	 * @todo siw_generate_link gebruiken
	 * @param  string $url
	 * @param  string $text
	 * @return string
	 */
	public static function generate_external_link( $url, $text = false ) {

		if ( false == $text ) {
			$text = $url;
		}
		$external_link = sprintf( '<a class="siw-external-link" href="%s" target="_blank" rel="noopener">%s&nbsp;<i class="kt-icon-newtab"></i></a>', esc_url( $url ), esc_html( $text ) );

		return $external_link;
	}

	/**
	 * Genereer pinnacle accordion
	 * @param  array $panes
	 * @return string
	 */
	public static function generate_accordion( $panes ) {
		if ( empty( $panes) ) {
			return;
		}
		$accordion = '[accordion]';
		foreach ( $panes as $pane ) {
			if ( empty( $pane['content'] ) ) {
				continue;
			}
			if ( isset( $pane['show_button'] ) && true == $pane['show_button'] ) {
				$pane['content'] .= wpautop( $self::generate_link( $pane['button_url'], $pane['button_text'], 'kad-btn' ) );
			}
			$accordion .= sprintf( '[pane title="%s"]%s[/pane]', esc_html( $pane['title'] ), wp_kses_post( wpautop( $pane['content'] )  ) );
		}
		$accordion .= '[/accordion]';
		return $accordion;
	}

	/**
	 * Rendert template o.b.v. variabelen
	 *
	 * @param string $template
	 * @param array $vars
	 * @return string
	 */
	public static function render_template( $template, $vars ) {
		$variables = [];
		foreach ( $vars as $key => $value ) {
			$variables[ '{{ ' . $key . ' }}' ] = $value;
		}
		return strtr( $template, $variables );
	}

	/**
	 * Genereert script-tag met JSON-LD op basis van array
	 *
	 * @param array $data
	 * @return string
	 */
	public static function generate_json_ld( $data ) {
		ob_start();
		?>
		<script type="application/ld+json">
		<?php echo json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);?>
		</script>
		<?php
		$json_ld = ob_get_clean();
		return $json_ld;
	}

	/**
	* sanitize_html_class voor meerdere classes
	*
	* @uses   sanitize_html_class()
	* @param  mixed $class
	* @param  string $fallback
	* @return string
	*/
	protected static function sanitize_html_classes( $class, $fallback = null ) {
		if ( is_string( $class ) ) {
			$class = explode( ' ', $class );
		}
		if ( is_array( $class ) && count( $class ) > 0 ) {
			$class = array_map( 'sanitize_html_class', $class );
			return implode( ' ', $class );
		}
		else {
			return sanitize_html_class( $class, $fallback );
		}
	}

	/**
	 * Formatteert datum als tekst
	 *
	 * @param string $date Y-m-d
	 * @param boolean $year jaar toevoegen aan tekst
	 * @return string
	 */
	public static function format_date( $date, $year = true ) {
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
	public static function format_date_range( $date_start, $date_end, $year = true ) {
		
		if ( $date_start == $date_end ) {
			return $self::format_date( $date_start, $year );
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

		$date_start_in_text = date_i18n( $format_start, strtotime( $date_start ) );
		$date_end_in_text = date_i18n( $format_end, strtotime( $date_end ) );

		$date_range_in_text = sprintf( __( '%s t/m %s', 'siw' ), $date_start_in_text, $date_end_in_text );

		return $date_range_in_text;
	}
	

	/**
	 * Formatteert maand uit datum als tekst
	 *
	 * @param string $date Y-m-d
	 * @param bool $year Jaar toevoegen aan tekst
	 *
	 * @return string
	 */
	function format_month( $date, $year = true ) {
		$format = $year ? 'F Y' :  'F';
		return date_i18n( $format, strtotime( $date ) );
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
	function format_month_range( $date_start, $date_end, $year = true ) {

		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		if ( $date_start == $date_end || $date_start_array['month'] == $date_end_array['month'] ) {
			return $self::format_month( $date_start, $year );
		}

		$format_end = $year ? 'F Y' :  'F';
		if ( $year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
			$format_start = 'F Y';
		}
		else {
			$format_start = 'F';
		}


		$month_start_in_text = date_i18n( $format_start, strtotime( $date_start ) );
		$month_end_in_text = date_i18n( $format_end, strtotime( $date_end ) );

		$month_range_in_text = sprintf( __( '%s t/m %s', 'siw' ), $month_start_in_text, $month_end_in_text );

		return $month_range_in_text;
	}

	/**
	 * Genereert css o.b.v. array met regels
	 *
	 * @param array $rules
	 * @return string
	 */
	public static function generate_css( $rules ) {
		$css = '';
		foreach ( $rules as $selector => $styles ) {
			$css .= $selector . '{';
			foreach ( $styles as $property => $value ) {
				$css .= $property . ':' . $value . ';';
			}
			$css .= '}';
		}
	
		return $css;
	}

}
