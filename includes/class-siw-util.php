<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hulpfuncties
 *
 * @package   SIW
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Util {

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

	/**
	 * Undocumented function
	 *
	 * @param string $script
	 * @return string
	 */
	public static function generate_anonymous_jquery( $script ) {
		return sprintf('(function( $ ) {%s})( jQuery );', $script );
	}

	/**
	 * Geeft validatiepatroon terug
	 *
	 * @param string $type
	 * @return string
	 */
	public static function get_pattern( $type ) {
		$patterns = [
			'date'        => '^(0?[1-9]|[12]\d|3[01])[\-](0?[1-9]|1[012])[\-]([12]\d)?(\d\d)$',
			'postal_code' => '^[1-9][0-9]{3}\s?[a-zA-Z]{2}$',
			'postcode'    => '^[1-9][0-9]{3}\s?[a-zA-Z]{2}$',
			'latitude'    => '^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$',
			'longitude'   => '^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$',
			'ip'          => '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$',
		];
		return $patterns[ $type ] ?? false;
	}

	/**
	 * Geeft reguliere expressie terug
	 *
	 * @param string $type
	 * @return string
	 */
	public static function get_regex( $type ) {

		$pattern = self::get_pattern( $type );
		if ( false == $pattern ) {
			return false;
		}
		$regex = sprintf( '/%s/', $pattern );
		return $regex;
	}

	/**
	 * Geeft array met pagina's in standaardtaal terug
	 * 
	 * @return array
	 */
	public static function get_pages() {
		$default_lang = apply_filters( 'wpml_default_language', NULL );
		$current_lang = apply_filters( 'wpml_current_language', NULL );
		do_action( 'wpml_switch_language', $default_lang );
		$results = get_pages();
		do_action( 'wpml_switch_language', $current_lang );

		$pages = [];
		foreach ( $results as $result ) {
			$ancestors = get_ancestors( $result->ID, 'page' );
			$prefix = str_repeat ( '-', sizeof( $ancestors ) );
			$pages[ $result->ID ] = $prefix . esc_html( $result->post_title );
		}
		return $pages;
	}
	
	/**
	 * Berekent leeftijd in jaren o.b.v. huidige datum
	 *
	 * @param  string $date dd-mm-jjjj
	 * @return int leeftijd in jaren
	 */
	public static function calculate_age( $date ) {
		$from = new DateTime( $date );
		$to   = new DateTime('today');
		$age = $from->diff( $to )->y;

		return $age;
	}

	/**
	 * Undocumented function
	 *
	 * @param int $timestamp
	 * @return int
	 */
	public static function convert_timestamp_to_gmt( $timestamp ) {
		$timestamp_in_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $timestamp ) ) . ' GMT' );
		return $timestamp_in_gmt;
	}

	/**
	 * Zet SEO noindex
	 * 
	 * @param int $post_id
	 * @param bool $value
	 */
	public static function set_seo_noindex( $post_id, $value = false ) {
		$noindex = $value ? 1 : 0;
		update_post_meta( $post_id, '_genesis_noindex', $noindex );
	}

	/**
	 * Zet SEO meta title
	 * 
	 * @param int $post_id
	 * @param string $title
	 */
	public static function set_seo_title( $post_id, $title ) {
		update_post_meta( $post_id, '_genesis_title', $title );
	}

	/**
	 * Zet SEO meta description
	 * 
	 * @param int $post_id
	 * @param string $description
	 */
	public static function set_seo_description( $post_id, $description ) {
		update_post_meta( $post_id, '_genesis_description', $description );
	}
}