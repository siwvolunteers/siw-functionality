<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

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

		if ( ! $this->show_archive_header() ) {
			return;
		}

		$text = implode(
			BR2,
			array_filter(
				[
					$this->get_intro_text(),
					$this->get_teaser_text(),
				]
			)
		);
	
		?>
		<div class="grid-container">
			<div class="siw-archive-intro">
				<?php echo wp_kses_post( $text ); ?>
			</div>
		</div>
		
		<?php
		/** 
		*alert text tonen? */
		if ( ! $text = $this->get_alert_text()) {
			return;
		}
		?>
		<div class="grid-container">
			<div class="siw-archive-alert">
				<?php echo wp_kses_post( $text ); ?>
			</div>
		</div>
		<?php
	}

	/** Geeft aan of header getoond moet worden */
	protected function show_archive_header() : bool {
		return \is_shop() || \is_product_category() || \is_product_taxonomy();
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
				case Taxonomy_Attribute::COUNTRY()->value:
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case Taxonomy_Attribute::WORK_TYPE()->value:
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::SDG()->value:
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op het Sustainable Development Goal %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case Taxonomy_Attribute::TARGET_AUDIENCE()->value:
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten voor de doelgroep %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::LANGUAGE()->value:
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met de voertaal %s.', 'siw' ), '<b>' . ucfirst( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::MONTH()->value:
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in de maand %s.', 'siw' ), '<b>' . ucfirst( $name ) . '</b>' );
					break;
				default:
					$text = __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten.', 'siw' );
			}
		}
		
		$workcamps_page_link = i18n::get_translated_page_url( intval( siw_get_option( 'pages.explanation.workcamps' ) ) );

		$text .= SPACE .
			__( 'Tijdens onze Groepsprojecten ga je samen met een internationale groep vrijwilligers voor 2 á 3 weken aan de slag.', 'siw' ) . SPACE .
			__( 'De projecten hebben vaste begin- en einddata.', 'siw' ) . SPACE .
			sprintf( __( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina <a href="%s">Groepsprojecten</a>.', 'siw' ), esc_url( $workcamps_page_link ) );

		return $text;
	}
	/**
	*	alert text todo: bij instellingen opnemen
	*/
	protected function get_alert_text() : ?string {
		$text = "";
		if ( \is_product_category() ) {
			$category_name = get_queried_object()->name;
			if($category_name == "Europa")
			{
				$text .= ''; // tijdelijk tekst voor europareizen verwijderd.
			}
		}
		return($text);
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

	/** Genereert aankondiging voor nieuwe projecten */
	protected function get_teaser_text() : ?string {

		if ( ! $this->is_teaser_text_active() ) {
			return null;
		}

		$teaser_text = siw_get_option( 'workcamp_teaser_text' );

		$contact_page_link = i18n::get_translated_page_url( intval( siw_get_option( 'pages.contact' ) ) );
		$end_year = date( 'Y', strtotime( $teaser_text['end_date'] ) );
		$end_month = date_i18n( 'F', strtotime( $teaser_text['end_date'] ) );
		$teaser_text = sprintf( __( 'Vanaf %s wordt het aanbod aangevuld met honderden nieuwe vrijwilligersprojecten voor %s.', 'siw' ), $end_month, $end_year ). SPACE .
			__( 'Wil je nu al meer weten over de grensverleggende mogelijkheden van SIW?', 'siw' ) . SPACE .
			sprintf( __( '<a href="%s">Bel of mail ons</a> en we denken graag met je mee!', 'siw' ), esc_url( $contact_page_link ) );
		
		return $teaser_text;
	}

}
