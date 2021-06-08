<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Core\Template;
use SIW\i18n;
use SIW\Properties;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Header voor overzichtspagina van groepsprojecten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Archive_Header {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'generate_inside_site_container', [ $self, 'add_archive_description'] );
	}

	/** Toont beschrijving van overzichtspagina */
	public function add_archive_description() {

		if ( ! ( \is_shop() || \is_product_category() || \is_product_taxonomy() ) ) {
			return;
		}

		Template::render_template(
			'woocommerce/archive-header',
			[
				'intro_text'           => $this->get_intro_text(),
				'explanation_page_url' => i18n::get_translated_page_url( intval( siw_get_option( 'pages.explanation.workcamps' ) ) ),
				'sale' => [
					'is_active' => \siw_is_workcamp_sale_active(),
					'end_date'  => \siw_is_workcamp_sale_active() ? \siw_format_date( siw_get_option( 'workcamp_sale.end_date' ), false ) : '',
				],
				'tariffs' => [
					'regular'      => \siw_format_amount( Properties::WORKCAMP_FEE_REGULAR ),
					'regular_sale' => siw_format_amount( Properties::WORKCAMP_FEE_REGULAR_SALE ),
					'student'      => siw_format_amount( Properties::WORKCAMP_FEE_STUDENT ),
					'student_sale' => siw_format_amount( Properties::WORKCAMP_FEE_STUDENT_SALE ),
				],
				'contact_page_url' => i18n::get_translated_page_url( intval( siw_get_option( 'pages.contact' ) ) ),
				'teaser' => [
					'active' => $this->is_teaser_text_active(),
					'month'  => $this->is_teaser_text_active() ? date_i18n( 'F', siw_get_option( 'workcamp_teaser_text.end_date' ) ) : '',
					'year'   => $this->is_teaser_text_active() ? date( 'Y', strtotime( siw_get_option( 'workcamp_teaser_text.end_date' ) ) ) : '',
				],

			]
		);
	}

	/** Genereert introtekst */
	protected function get_intro_text() : string {

		if ( \is_shop() ) {
			$text = __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten.', 'siw' );
		}
		elseif ( \is_product_category() ) {
			$category_name = get_queried_object()->name;
			$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $category_name . '</b>' );
		}
		elseif ( \is_product_taxonomy() ) {
			$name = get_queried_object()->name;
			switch ( get_queried_object()->taxonomy ) {
				case Taxonomy_Attribute::CONTINENT():
				case Taxonomy_Attribute::COUNTRY():
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case Taxonomy_Attribute::WORK_TYPE():
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::SDG():
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op het Sustainable Development Goal %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case Taxonomy_Attribute::TARGET_AUDIENCE():
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten voor de doelgroep %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::LANGUAGE():
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met de voertaal %s.', 'siw' ), '<b>' . ucfirst( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::MONTH():
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in de maand %s.', 'siw' ), '<b>' . ucfirst( $name ) . '</b>' );
					break;
				default:
					$text = __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten.', 'siw' );
			}
		}
		return $text;
	}

	/** Geeft aan of aankondiging nieuwe projecten getoond moet worden */
	protected function is_teaser_text_active() : bool {
		$teaser_text = siw_get_option( 'workcamp_teaser_text' );
		$teaser_text_active = false;
		if ( isset( $teaser_text['active'] ) &&
			$teaser_text['active'] &&
			date( 'Y-m-d' ) >= $teaser_text['start_date'] &&
			date( 'Y-m-d' ) <= $teaser_text['end_date']
			) {
				$teaser_text_active = true;
		}
		return $teaser_text_active;
	}
}
