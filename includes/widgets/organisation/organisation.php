<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Properties;
use SIW\Util\Links;

/**
 * Widget met organisatiegegevens
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Organisatiegegevens
 * Description: Toont organisatiegegevens.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Organisation extends Widget {

	/** {@inheritDoc} */
	protected string $widget_id = 'organisation';

	/** {@inheritDoc} */
	protected string $widget_dashicon = 'building';

	/** {@inheritDoc} */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Organisatiegegevens', 'siw');
		$this->widget_description = __( 'Toont organisatiegegevens', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'      => 'text',
				'label'     => __( 'Titel', 'siw'),
				'default'   => __( 'Gegevens', 'siw' ),
			],
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
	function get_template_variables( $instance, $args ) {
		$parameters = [
			'properties' => [
				[
					'name'   => __( 'Statutaire naam', 'siw' ),
					'values' => Properties::STATUTORY_NAME
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
			]
		];

		return $parameters;
	}

	/** Geeft jaarverslagen terug */
	protected function get_annual_reports() : array {
		$annual_reports = siw_get_option( 'annual_reports' );
		if ( empty( $annual_reports ) ) {
			return [];
		}
		
		$annual_reports = array_column( $annual_reports , null, 'year' );
		krsort( $annual_reports );

		$annual_reports = array_map(
			fn( array $report ) : array => [
				'url'  => wp_get_attachment_url( $report['file'][0] ),
				'text' => sprintf( __( 'Jaarverslag %s', 'siw' ), $report['year'] )
			],
			$annual_reports
		);
		return array_values( $annual_reports );
	}
}
