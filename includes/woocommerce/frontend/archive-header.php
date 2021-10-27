<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\i18n;
use SIW\Properties;

/**
 * Header voor overzichtspagina van groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
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
					$this->get_sale_text(),
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
				case 'pa_land':
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case 'pa_soort-werk':
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case 'pa_sdg':
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op het Sustainable Development Goal %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case 'pa_doelgroep':
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten voor de doelgroep %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case 'pa_taal':
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met de voertaal %s.', 'siw' ), '<b>' . ucfirst( $name ) . '</b>' );
					break;
				case 'pa_maand':
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
	*	tijdelijke alert voor europareizen
	*/
	protected function get_alert_text() : ?string {
		$text = "";
		if ( \is_product_category() ) {
			$category_name = get_queried_object()->name;
			if($category_name == "Europa")
			{
				$text .= SPACE .
				__('Als SIW zenden wij uit naar landen die op "groen" of "geel" staan.', 'siw' ) . SPACE .
				__('Actuele informatie is te vinden op de website Nederland wereldwijd van de Rijksoverheid <a href="https://www.nederlandwereldwijd.nl/reizen/reisadviezen">reisadviezen oveheid</a>','siw') .SPACE .
				__('Je hoeft nu niet direct te betalen.','siw') .SPACE .
				__('Wij kijken na aanmelding of het project doorgaat en of je geplaatst kan worden.','siw') .SPACE .
				__('Pas als het zeker is dat je ook daadwerkelijk naar de plaats van bestemming kan afreizen, doe je de betaling aan SIW. Niet eerder!','siw') .SPACE .
				__('Mocht het door jou gekozen project niet doorgaan, dan kunnen we natuurlijk samen kijken naar een ander gelijksoortig project waaraan je wel kunt deelnemen.','siw') .SPACE .
				'<i>';
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

	/** Genereert tekst voor kortingsactie */
	protected function get_sale_text() : ?string {

		if ( ! siw_is_workcamp_sale_active() ) {
			return null;
		}

		$regular = siw_format_amount( Properties::WORKCAMP_FEE_REGULAR );
		$regular_sale = siw_format_amount( Properties::WORKCAMP_FEE_REGULAR_SALE );
		$student = siw_format_amount( Properties::WORKCAMP_FEE_STUDENT );
		$student_sale = siw_format_amount( Properties::WORKCAMP_FEE_STUDENT_SALE );
		$end_date = siw_format_date( siw_get_option( 'workcamp_sale.end_date' ), false );
	
		$sale_text = sprintf( __( 'Meld je nu aan en betaal geen %s maar %s voor je vrijwilligersproject.', 'siw' ), $regular, '<b>'. $regular_sale .'</b>' ) . SPACE .
			__( 'Ben je student of jonger dan 18 jaar?', 'siw' ) . SPACE .
			sprintf( __( 'Dan betaal je in plaats van %s nog maar %s.', 'siw' ), $student, '<b>'. $student_sale .'</b>' ) . BR  .
			'<b>' . __( 'Let op:', 'siw' ) . '</b>' . SPACE .
			sprintf( __( 'Deze actie duurt nog maar t/m %s, dus wees er snel bij.', 'siw' ), $end_date );

		return $sale_text;
	}
}
