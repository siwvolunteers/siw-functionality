<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Archive;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Project_Type;
use SIW\Data\Special_Page;
use SIW\Data\Work_Type;
use SIW\WooCommerce\Taxonomy_Attribute;

class Header extends Base {

	#[Add_Action( 'generate_inside_site_container' )]
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
			<div class="siw-intro">
				<?php echo wp_kses_post( $text ); ?>
			</div>
		</div>

		<?php
	}

	protected function show_archive_header(): bool {
		return \is_shop() || \is_product_category() || \is_product_taxonomy();
	}

	protected function get_intro_text(): string {

		if ( \is_shop() ) {
			$text = __( 'Hieronder zie je het beschikbare aanbod projecten.', 'siw' );
		} elseif ( \is_product_category() ) {
			$category_name = get_queried_object()->name;
			// translators: %s is het continent
			$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod %s-projecten.', 'siw' ), '<b>' . $category_name . '</b>' );
		} elseif ( \is_product_taxonomy() ) {
			$name = get_queried_object()->name;
			switch ( get_queried_object()->taxonomy ) {
				case Taxonomy_Attribute::CONTINENT->value:
				case Taxonomy_Attribute::COUNTRY->value:
					// translators: %s is het land
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod projecten in %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case Taxonomy_Attribute::WORK_TYPE->value:
					// translators: %s is het soort werk
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod projecten met werkzaamheden gericht op %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::SDG->value:
					// translators: %s is het SDG
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod projecten met werkzaamheden gericht op het Sustainable Development Goal %s.', 'siw' ), '<b>' . $name . '</b>' );
					break;
				case Taxonomy_Attribute::TARGET_AUDIENCE->value:
					// translators: %s is de doelgroep
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod projecten voor de doelgroep %s.', 'siw' ), '<b>' . strtolower( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::LANGUAGE->value:
					// translators: %s is de taal
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod projecten met de voertaal %s.', 'siw' ), '<b>' . ucfirst( $name ) . '</b>' );
					break;
				case Taxonomy_Attribute::MONTH->value:
					// translators: %s is de maand
					$text = sprintf( __( 'Hieronder zie je het beschikbare aanbod projecten in de maand %s.', 'siw' ), '<b>' . ucfirst( $name ) . '</b>' );
					break;
				default:
					$text = __( 'Hieronder zie je het beschikbare aanbod projecten.', 'siw' );
			}
		}

		if (
			\is_product_taxonomy()
			&& get_queried_object()->taxonomy === Taxonomy_Attribute::WORK_TYPE->value
			&& Work_Type::tryFrom( get_queried_object()->slug )?->needs_review()
		) {
			$text .= BR . 'Aangezien je in deze projecten met kinderen gaat werken, stellen wij het verplicht om een VOG (Verklaring Omtrent Gedrag) aan te vragen.';
		}

		$workcamps_page = siw_get_project_type_page( Project_Type::WORKCAMPS );

		$text .= BR .
			__( 'Tijdens onze Groepsprojecten ga je samen met een internationale groep vrijwilligers voor 2 á 3 weken aan de slag.', 'siw' ) . SPACE .
			__( 'De projecten hebben vaste begin- en einddata.', 'siw' ) . SPACE .
			// translators: %s is een url
			sprintf( __( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina <a href="%s">Groepsprojecten</a>.', 'siw' ), esc_url( get_permalink( $workcamps_page ?? 0 ) ) );

		return $text;
	}

	protected function is_teaser_text_active(): bool {
		$teaser_text = siw_get_option( 'workcamp_teaser_text' );
		$teaser_text_active = false;
		if ( isset( $teaser_text['active'] ) &&
			$teaser_text['active'] &&
			gmdate( 'Y-m-d' ) >= $teaser_text['start_date'] &&
			gmdate( 'Y-m-d' ) <= $teaser_text['end_date']
			) {
				$teaser_text_active = true;
		}
		return $teaser_text_active;
	}

	protected function get_teaser_text(): ?string {

		if ( ! $this->is_teaser_text_active() ) {
			return null;
		}

		$teaser_text = siw_get_option( 'workcamp_teaser_text' );

		$contact_page = siw_get_special_page( Special_Page::CONTACT );
		$end_year = gmdate( 'Y', strtotime( $teaser_text['end_date'] ) );
		$end_month = date_i18n( 'F', strtotime( $teaser_text['end_date'] ) );
		// translators: %1$s is een maand,  %2$s is een jaar
		$teaser_text = sprintf( __( 'Vanaf %1$s wordt het aanbod aangevuld met honderden nieuwe vrijwilligersprojecten voor %2$s.', 'siw' ), $end_month, $end_year ) . SPACE .
			__( 'Wil je nu al meer weten over de grensverleggende mogelijkheden van SIW?', 'siw' ) . SPACE .
			// translators: %s is link van contactpagina
			sprintf( __( '<a href="%s">Bel of mail ons</a> en we denken graag met je mee!', 'siw' ), esc_url( get_permalink( $contact_page ?? 0 ) ) );

		return $teaser_text;
	}
}
