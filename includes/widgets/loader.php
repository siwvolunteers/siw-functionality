<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;

/**
 * Loader voor Widgets
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Class_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Accordion::class,
			Annual_Reports::class,
			Board_Members::class,
			Calendar::class,
			Carousel::class,
			Contact::class,
			CTA::class,
			Featured_Image::class,
			Form::class,
			Icon::class,
			Interactive_Map::class,
			Map::class,
			Newsletter_Confirmation::class,
			Organisation::class,
			Pie_Chart::class,
			Project_Wizard::class,
			Quote::class,
			Social_Links::class,
			Sponsors::class,
			Subpages::class,
			Tabs::class,
			YouTube_Video::class,
		];
	}

	/** {@inheritDoc} */
	public function load( string $class_name ) {
		$id_base = $this->get_id_base_from_class( $class_name );
		$file_base = $this->get_file_base_from_id_base( $id_base );

		$widget_folder = untrailingslashit( SIW_WIDGETS_DIR );
		if ( function_exists( 'siteorigin_widget_register' ) && file_exists( "{$widget_folder}/{$file_base}/{$file_base}.php" ) ) {
			siteorigin_widget_register(
				"sow-siw_{$id_base}_widget",
				"{$widget_folder}/{$file_base}/{$file_base}.php",
				"\\{$class_name}"
			);
			require_once "{$widget_folder}/{$file_base}/{$file_base}.php";
		}

		add_filter( 'siteorigin_widgets_active_widgets', fn( $active_widgets ) => wp_parse_args( [ $id_base => true ], $active_widgets ) );
		// Widget activeren, kan pas bij init-hook
		if ( class_exists( \SiteOrigin_Widgets_Bundle::class ) ) {
			add_action( 'init', fn() => \SiteOrigin_Widgets_Bundle::single()->activate_widget( $file_base ) );
		}
	}

	/** Zet FQN om naar id-base */
	protected function get_id_base_from_class( string $class_name ): string {
		$id_base = explode( '\\', $class_name );
		return strtolower( end( $id_base ) );
	}

	/** Zet id_base om naar file-base */
	protected function get_file_base_from_id_base( string $id_base ): string {
		return str_replace( '_', '-', $id_base );
	}
}
