<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Facades\SiteOrigin;

class Widgets extends Base {

	public function get_widgets(): array {
		return [
			\SIW\Widgets\Accordion::class,
			\SIW\Widgets\Annual_Reports::class,
			\SIW\Widgets\Board_Members::class,
			\SIW\Widgets\Calendar::class,
			\SIW\Widgets\Carousel::class,
			\SIW\Widgets\Contact::class,
			\SIW\Widgets\CTA::class,
			\SIW\Widgets\Dutch_Projects::class,
			\SIW\Widgets\Featured_Image::class,
			\SIW\Widgets\Form::class,
			\SIW\Widgets\Interactive_Map::class,
			\SIW\Widgets\Newsletter_Confirmation::class,
			\SIW\Widgets\Organisation::class,
			\SIW\Widgets\Pie_Chart::class,
			\SIW\Widgets\Project_Wizard::class,
			\SIW\Widgets\Quote::class,
			\SIW\Widgets\Social_Links::class,
			\SIW\Widgets\Sponsors::class,
			\SIW\Widgets\Subpages::class,
			\SIW\Widgets\YouTube_Video::class,
		];
	}

	#[Add_Action( 'widgets_init', 1 )]
	public function register_widgets() {
		foreach ( $this->get_widgets() as $widget ) {
			$this->register_widget( $widget );
		}
	}

	protected function register_widget( string $class_name ) {
		$id_base = $this->get_id_base_from_class( $class_name );
		$file_base = $this->get_file_base_from_id_base( $id_base );
		$widget_folder = untrailingslashit( SIW_WIDGETS_DIR );
		$file_name = "{$widget_folder}/{$file_base}/{$file_base}.php";

		if ( file_exists( $file_name ) ) {
			SiteOrigin::widget_register(
				"sow-siw_{$id_base}_widget",
				$file_name,
				"\\{$class_name}"
			);
			require_once $file_name;
		}

		add_filter( 'siteorigin_widgets_active_widgets', fn( $active_widgets ) => wp_parse_args( [ $id_base => true ], $active_widgets ) );

		// Widget activeren, kan pas bij init-hook
		add_action( 'init', fn() => SiteOrigin::activate_widget( $file_base ) );
	}

	protected function get_id_base_from_class( string $class_name ): string {
		$id_base = explode( '\\', $class_name );
		return strtolower( end( $id_base ) );
	}

	protected function get_file_base_from_id_base( string $id_base ): string {
		return str_replace( '_', '-', $id_base );
	}
}
