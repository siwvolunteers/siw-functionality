<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Properties;

/**
 * Widget met organisatiegegevens
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Organisatiegegevens
 * Description: Toont organisatiegegevens.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Organisation extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'organisation';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Organisatiegegevens', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont organisatiegegevens', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'building';
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
		$widget_form = [
			'renumeration_policy' => [
				'type'           => 'tinymce',
				'label'          => __( 'Beloningsbeleid', 'siw' ),
				'rows'           => 10,
				'default_editor' => 'html',
			],
		];
		return $widget_form;
	}

	/** Geeft bestuursleden terug */
	protected function get_board_members() : ?array {
		$board_members = siw_get_option( 'board_members' );
		if ( empty( $board_members ) ) {
			return null;
		}

		return array_map(
			fn( array $board_member ) : array => [
				'first_name' => $board_member['first_name'],
				'last_name'  => $board_member['last_name'],
				'title'      => siw_get_board_title( $board_member['title'] ),
			],
			$board_members
		);
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		$parameters = [
			'properties'          => [
				[
					'name'   => __( 'Statutaire naam', 'siw' ),
					'values' => Properties::STATUTORY_NAME,
				],
				[
					'name'   => __( 'RSIN/fiscaal nummer', 'siw' ),
					'values' => Properties::RSIN,
				],
				[
					'name'   => __( 'KVK-nummer', 'siw' ),
					'values' => Properties::KVK,
				],
				[
					'name'   => __( 'Rekeningnummer', 'siw' ),
					'values' => Properties::IBAN,
				],
			],
			'board_members'       => $this->get_board_members(),
			'annual_reports'      => $this->get_annual_reports(),
			'renumeration_policy' => $instance['renumeration_policy'],
			'i18n'                => [
				'board_members'       => __( 'Bestuurssamenstelling', 'siw' ),
				'annual_reports'      => __( 'Jaarverslagen', 'siw' ),
				'renumeration_policy' => __( 'Beloningsbeleid', 'siw' ),
			],
		];

		return $parameters;
	}

	/** Geeft jaarverslagen terug */
	protected function get_annual_reports() : array {
		$annual_reports = siw_get_option( 'annual_reports' );
		if ( empty( $annual_reports ) ) {
			return [];
		}

		$annual_reports = array_column( $annual_reports, null, 'year' );
		krsort( $annual_reports );

		$annual_reports = array_map(
			fn( array $report ) : array => [
				'url'  => wp_get_attachment_url( $report['file'][0] ),
				// translators: %s is een jaartal
				'text' => sprintf( __( 'Jaarverslag %s', 'siw' ), $report['year'] ),
			],
			$annual_reports
		);
		return array_values( $annual_reports );
	}
}
