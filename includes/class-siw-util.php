<?php

/**
 * Hulpfuncties
 *
 * @package   SIW
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Util {

	/**
	 * Genereert css o.b.v. array met regels
	 *
	 * @param array $rules
	 * @return string
	 */
	public static function generate_css( array $rules ) {
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

	/**
	 * Undocumented function
	 *
	 * @param string $script
	 * @return string
	 */
	public static function generate_anonymous_jquery( string $script ) {
		return sprintf('(function( $ ) {%s})( jQuery );', $script );
	}

	/**
	 * Geeft validatiepatroon terug
	 *
	 * @param string $type
	 * @return string
	 */
	public static function get_pattern( string $type ) {
		$patterns = [
			'date'        => '^(0?[1-9]|[12]\d|3[01])[\-](0?[1-9]|1[012])[\-]([12]\d)?(\d\d)$',
			'postal_code' => '^[1-9][0-9]{3}\s?[a-zA-Z]{2}$',
			'postcode'    => '^[1-9][0-9]{3}\s?[a-zA-Z]{2}$',
			'latitude'    => '^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$',
			'longitude'   => '^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$',
			'ip'          => '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$',
		];
		return $patterns[ $type ] ?? null;
	}

	/**
	 * Geeft reguliere expressie terug
	 *
	 * @param string $type
	 * @return string
	 */
	public static function get_regex( string $type ) {

		$pattern = self::get_pattern( $type );
		if ( null === $pattern ) {
			return null;
		}
		$regex = sprintf( '/%s/', $pattern );
		return $regex;
	}

	/**
	 * Geeft array met pagina's in standaardtaal terug
	 * 
	 * @return array
	 * 
	 * @todo https://docs.metabox.io/custom-select-checkbox-tree/
	 */
	public static function get_pages() {
		$default_lang = SIW_i18n::get_default_language();
		$current_lang = SIW_i18n::get_current_language();
		do_action( 'wpml_switch_language', $default_lang );
		$results = get_pages();
		do_action( 'wpml_switch_language', $current_lang );

		$pages = [];
		foreach ( $results as $result ) {
			$ancestors = array_reverse( get_ancestors( $result->ID, 'page') );
			$callback = function( &$value, $key ) {
				$value = get_the_title( $value );
			};
			array_walk( $ancestors, $callback );
			$prefix = ! empty( $ancestors ) ? implode( '/', $ancestors ) . '/' : '';
			$pages[ $result->ID ] = esc_html( $prefix . $result->post_title );
		}
		return $pages;
	}
	
	/**
	 * Berekent leeftijd in jaren o.b.v. huidige datum
	 *
	 * @param  string $date dd-mm-jjjj
	 * @return int leeftijd in jaren
	 */
	public static function calculate_age( string $date ) {
		$from = new DateTime( $date );
		$to   = new DateTime('today');
		$age = $from->diff( $to )->y;

		return $age;
	}

	/**
	 * Converteert timestamp met tijdzone naar timestamp in GMT
	 *
	 * @param int $timestamp
	 * @return int
	 */
	public static function convert_timestamp_to_gmt( int $timestamp ) {
		$timestamp_in_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $timestamp ) ) . ' GMT' );
		return $timestamp_in_gmt;
	}

	/**
	 * Undocumented function
	 *
	 * @param int $post_id
	 * @return bool
	 */
	public static function get_seo_noindex( int $post_id ) {
		$noindex = get_post_meta( $post_id, '_genesis_noindex', true );
		return (bool) $noindex;
	}

	/**
	 * Zet SEO noindex
	 * 
	 * @param int $post_id
	 * @param bool $value
	 */
	public static function set_seo_noindex( int $post_id, bool $value = false ) {
		$noindex = $value ? 1 : 0;
		update_post_meta( $post_id, '_genesis_noindex', $noindex );
	}

	/**
	 * Zet SEO meta title
	 * 
	 * @param int $post_id
	 * @param string $title
	 */
	public static function set_seo_title( int $post_id, string $title ) {
		update_post_meta( $post_id, '_genesis_title', $title );
	}

	/**
	 * Zet SEO meta description
	 * 
	 * @param int $post_id
	 * @param string $description
	 */
	public static function set_seo_description( int $post_id, string $description ) {
		update_post_meta( $post_id, '_genesis_description', $description );
	}

	/**
	 * Geeft aan of kortingsactie voor Groepsprojecten actief is
	 *
	 * @return bool
	 */
	public static function is_workcamp_sale_active() {
		
		$workcamp_sale = siw_get_option( 'workcamp_sale' );
		$workcamp_sale_active = false;

		if ( $workcamp_sale['active'] &&
			date( 'Y-m-d' ) >= $workcamp_sale['start_date'] &&
			date( 'Y-m-d' ) <= $workcamp_sale['end_date']
			) {
				$workcamp_sale_active = true;
		}
		return $workcamp_sale_active;
	}

	/**
	 * Geeft aan of kortingsactie voor Projecten Op Maat actief is
	 *
	 * @return bool
	 */
	public static function is_tailor_made_sale_active() {
		
		$tailor_made_sale = siw_get_option( 'tailor_made_sale' );
		
		$tailor_made_sale_active = false;

		if ( $tailor_made_sale['active'] &&
			date( 'Y-m-d' ) >= $tailor_made_sale['start_date'] &&
			date( 'Y-m-d' ) <= $tailor_made_sale['end_date']
			) {
				$tailor_made_sale_active = true;
		}
		return $tailor_made_sale_active;
	}

	/**
	 * Geeft aan of template bestaat
	 *
	 * @param string $template
	 * @return bool
	 */
	public static function template_exists( string $template ) {
		return file_exists( SIW_TEMPLATES_DIR . "/{$template}" );
	}

	/**
	 * Geeft aan of post bestaat op basis van ID
	 *
	 * @param int $post_id
	 * @return string
	 */
	public static function post_exists( int $post_id ) {
		return is_string( get_post_status( $post_id ) );
	}
}
