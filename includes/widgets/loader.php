<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;
use SIW\Facades\SiteOrigin;

class Loader extends Class_Loader_Abstract {

	#[\Override]
	public function get_classes(): array {
		return [
			Accordion::class,
			Annual_Reports::class,
			Board_Members::class,
			Calendar::class,
			Carousel::class,
			Contact::class,
			CTA::class,
			Dutch_Projects::class,
			Featured_Image::class,
			Form::class,
			Interactive_Map::class,
			Newsletter_Confirmation::class,
			Organisation::class,
			Pie_Chart::class,
			Project_Wizard::class,
			Quote::class,
			Social_Links::class,
			Sponsors::class,
			Subpages::class,
			YouTube_Video::class,
		];
	}

	#[\Override]
	public function load( string $class_name ) {
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
