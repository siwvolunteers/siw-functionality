<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Properties;

/**
 * Widget met jaarverslagen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Openingstijden
 * Description: Toont openingstijden
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Annual_Reports extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'annual_reports';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Jaarverslagen', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont jaarverslagen', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'media-document';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
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

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		$annual_reports = siw_get_option( 'annual_reports' );
		if ( empty( $annual_reports ) ) {
			return [];
		}

		$annual_reports = array_column( $annual_reports, null, 'year' );
		krsort( $annual_reports );

		$annual_reports = array_map(
			fn( array $report ): array => [
				'url'  => wp_get_attachment_url( $report['file'][0] ),
				// translators: %s is een jaartal
				'text' => sprintf( __( 'Jaarverslag %s', 'siw' ), $report['year'] ),
			],
			$annual_reports
		);

		$number = (int) $instance['number'];

		if ( count( $annual_reports ) > $number ) {
			$annual_reports = array_slice( $annual_reports, 0, $number );
		}

		return [
			'intro'          => $instance['intro'],
			'annual_reports' => array_values( $annual_reports ),
		];
	}
}
