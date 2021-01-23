<?php declare(strict_types=1);

namespace SIW;

/**
 * Hulpfuncties
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Util {

	/** Geeft breakpoint voor mobile terug */
	public static function get_mobile_breakpoint() : int {
		return function_exists( 'siteorigin_panels_setting') ? siteorigin_panels_setting( 'mobile-width' ) : 780; //TODO: fallback in constante
	}

	/** Geeft breakpoint voor tablet terug */
	public static function get_tablet_breakpoint() : int {
		return function_exists( 'siteorigin_panels_setting') ? siteorigin_panels_setting( 'tablet-width' ) : 1024;  //TODO: fallback in constante
	}

	/** Geeft validatiepatroon terug
	 * @todo   patterns verplaatsen naar databestand
	 */
	public static function get_pattern( string $type ) : ?string {
		$patterns = [
			'date'        => '^(0?[1-9]|[12]\d|3[01])[\-](0?[1-9]|1[012])[\-]([12]\d)?(\d\d)$',
			'postal_code' => '^[1-9][0-9]{3}\s?[a-zA-Z]{2}$',
			'latitude'    => '^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$',
			'longitude'   => '^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$',
			'ip'          => '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$',
		];
		return $patterns[ $type ] ?? null;
	}

	/** Geeft reguliere expressie terug */
	public static function get_regex( string $type ) : ?string {

		$pattern = self::get_pattern( $type );
		if ( is_null( $pattern ) ) {
			return null;
		}
		return sprintf( '/%s/', $pattern );
	}

	/** Geeft array met pagina's in standaardtaal terug */
	public static function get_pages() : array {
		$default_lang = i18n::get_default_language();
		$current_lang = i18n::get_current_language();
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
	
	/** Berekent leeftijd in jaren o.b.v. huidige datum */
	public static function calculate_age( string $date ) : int {
		$from = new \DateTime( $date );
		$to   = new \DateTime('today');
		return $from->diff( $to )->y;
	}

	/** Geeft aan of kortingsactie voor Groepsprojecten actief is */
	public static function is_workcamp_sale_active() : bool {
		
		$workcamp_sale = siw_get_option( 'workcamp_sale' );
		$workcamp_sale_active = false;

		if ( isset( $workcamp_sale['active'] ) &&
			date( 'Y-m-d' ) >= $workcamp_sale['start_date'] &&
			date( 'Y-m-d' ) <= $workcamp_sale['end_date']
			) {
				$workcamp_sale_active = true;
		}
		return $workcamp_sale_active;
	}

	/** Geeft aan of kortingsactie voor Projecten Op Maat actief is */
	public static function is_tailor_made_sale_active() : bool {
		
		$tailor_made_sale = siw_get_option( 'tailor_made_sale' );
		
		$tailor_made_sale_active = false;

		if ( isset( $tailor_made_sale['active'] ) &&
			date( 'Y-m-d' ) >= $tailor_made_sale['start_date'] &&
			date( 'Y-m-d' ) <= $tailor_made_sale['end_date']
			) {
				$tailor_made_sale_active = true;
		}
		return $tailor_made_sale_active;
	}

	/** Geeft parameter uit request terug */
	public static function get_request_parameter( string $key, $default = '' ) : string {
	
		if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) {
			return $default;
		}
		return strip_tags( (string) wp_unslash( $_REQUEST[ $key ] ) );
	}

	/** Creëert term indien deze nog niet bestaat
	 *  @return int|bool
	 */
	public static function maybe_create_term( string $taxonomy, string $slug, string $name, $order = null ) {
		$term = get_term_by( 'slug', $slug, $taxonomy );
		
		//Als term al bestaat zijn we snel klaar
		if ( is_a( $term, \WP_Term::class ) ) {
			return $term->term_id;
		}

		//Anders nieuwe term aanmaken
		$new_term = wp_insert_term( $name, $taxonomy, [ 'slug' => $slug ] );
		if ( is_wp_error( $new_term ) ) {
			return false;
		}

		//Eventueel volgorde zetten
		if ( ! empty( $order ) ) {
			update_term_meta( $new_term['term_id'], "order", $order );
		}

		return $new_term['term_id'];
	}

	/** Geeft aan of het een productieomgeving betreft */
	public static function is_production() : bool {
		return 'production' == \wp_get_environment_type();
	}
}
