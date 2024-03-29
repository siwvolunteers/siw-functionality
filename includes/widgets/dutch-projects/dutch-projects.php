<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Elements\Unordered_List\List_Style_Position;
use SIW\Data\Icons\Dashicons;
use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Visibility_Class;
use SIW\Data\Work_Type;
use SIW\Elements\Accordion_Tabs;
use SIW\Elements\Leaflet_Map;
use SIW\Elements\Link;
use SIW\Elements\Unordered_List;
use SIW\Facades\WooCommerce;
use SIW\Util\I18n;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Widget Name: SIW: Nederlandse projecten
 * Description: Toont Nederlandse projecten
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Dutch_Projects extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Nederlandse projecten', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont Nederlandse projecten', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::ADMIN_HOME;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {

		$projects = $this->get_projects();
		if ( empty( $projects ) ) {
			return [];
		}

		$map = Leaflet_Map::create()
			->set_zoom( 7 )
			->add_classes( [ Visibility_Class::HIDE_ON_MOBILE->value, Visibility_Class::HIDE_ON_TABLET->value ] );

		$accordion = Accordion_Tabs::create();

		foreach ( $projects as $project ) {
			$map->add_marker(
				$project->get_latitude(),
				$project->get_longitude(),
				$project->get_name(),
				$this->get_project_properties( $project ) . $this->get_project_link( $project ),
			);

			$accordion->add_item(
				[
					'title'       => $project->get_name(),
					'content'     => $this->get_project_properties( $project ),
					'show_button' => I18n::is_default_language(),
					'button_url'  => $project->get_permalink(),
					'button_text' => __( 'Bekijk project', 'siw' ),
				]
			);
		}

		return [
			'content' => $map->generate() . $accordion->generate(),
		];
	}

	/**
	 * @return WC_Product_Project[]
	 */
	protected function get_projects(): array {
		$args = [
			'country' => 'nederland',
		];
		$projects = array_filter(
			WooCommerce::get_products( $args ),
			fn( WC_Product_Project $project ): bool => ! $project->is_hidden()
		);
		usort( $projects, fn( WC_Product_Project $project_1, WC_Product_Project $project_2 ) => strcmp( $project_1->get_sku(), $project_2->get_sku() ) );
		return $projects;
	}

	protected function get_project_properties( WC_Product_Project $project ): string {
		$duration = siw_format_date_range( $project->get_start_date(), $project->get_end_date() );
		$work_types = array_map(
			fn( Work_Type $work_type ): string => $work_type->label(),
			$project->get_work_types()
		);

		$sdgs = array_map(
			fn( Sustainable_Development_Goal $sdg ): string => $sdg->full_name(),
			$project->get_sustainable_development_goals()
		);

		$description[] = sprintf( __( 'Projectcode: %s', 'siw' ), $project->get_sku() ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		$description[] = sprintf( __( 'Data: %s', 'siw' ), $duration ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		$description[] = sprintf( __( 'Soort werk: %s', 'siw' ), implode( ', ', $work_types ) ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment

		if ( ! empty( $sdgs ) ) {
			$description[] = sprintf( __( 'Sustainable Development Goals: %s', 'siw' ), implode( ', ', $sdgs ) ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
			// TODO: icons gebruiken?
		}
		return Unordered_List::create()
			->add_items( $description )
			->set_list_style_position( List_Style_Position::OUTSIDE )
			->generate();
	}

	protected function get_project_link( WC_Product_Project $project ): ?string {
		if ( ! I18n::is_default_language() ) {
			return null;
		}
		return Link::create()
			->set_url( $project->get_permalink() )
			->set_text( __( 'Bekijk project', 'siw' ) )
			->add_class( 'button' )
			->generate();
	}
}
