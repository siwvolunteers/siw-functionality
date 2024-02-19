<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Elements\Unordered_List\List_Style_Type;
use SIW\Data\Icons\Dashicons;
use SIW\Elements\Unordered_List;
use SIW\Facades\Meta_Box;
use SIW\Properties;

/**
 * Widget Name: SIW: Openingstijden
 * Description: Toont openingstijden
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Annual_Reports extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Jaarverslagen', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont jaarverslagen', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::MEDIA_DOCUMENT;
	}

	#[\Override]
	protected function get_widget_fields(): array {
		$widget_fields = [
			'number' => [
				'type'    => 'slider',
				'label'   => __( 'Aantal', 'siw' ),
				'default' => 1,
				'min'     => 1,
				'max'     => Properties::MAX_ANNUAL_REPORTS,
			],
		];
		return $widget_fields;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {

		$annual_reports = Meta_Box::get_option( 'annual_reports' );
		if ( empty( $annual_reports ) ) {
			return [];
		}

		$annual_reports = array_column( $annual_reports, null, 'year' );
		krsort( $annual_reports );

		$annual_reports = array_map(
			fn( array $report ): string => sprintf(
				'<a href="%s" target="_blank" rel="noopener">%s</a>',
				wp_get_attachment_url( $report['file'][0] ),
				// translators: %s is een jaartal
				sprintf( __( 'Jaarverslag %s', 'siw' ), $report['year'] )
			),
			$annual_reports
		);

		$number = (int) $instance['number'];

		if ( count( $annual_reports ) > $number ) {
			$annual_reports = array_slice( $annual_reports, 0, $number );
		}

		return [
			'content' => Unordered_List::create()
				->add_items( $annual_reports )
				->set_list_style_type( List_Style_Type::DISC )
				->generate(),
		];
	}
}
