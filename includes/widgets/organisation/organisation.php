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

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id = 'organisation';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'building';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Organisatiegegevens', 'siw');
		$this->widget_description = __( 'Toont organisatiegegevens', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
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

	/**
	 * Geeft lijst met bestuursleden terug
	 * 
	 * @return array|null
	 */
	protected function get_board_members_list() : ?array {
		$board_members = siw_get_option( 'board_members');
		if ( empty( $board_members ) ) {
			return null;;
		}
	
		$board_members_list = [];
		foreach ( $board_members as $board_member ) {
			$board_members_list[] = sprintf('%s %s<br/><i>%s</i>', $board_member['first_name'], $board_member['last_name'], $board_member['title']);
		}
		return $board_members_list;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_template_parameters(array $instance, array $args, array $template_vars, string $css_name): array {
		$parameters[ 'properties'] = [
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
			[
				'name'   => __( 'Bestuurssamenstelling', 'siw' ),
				'values' => $this->get_board_members_list(),
			],
			[
				'name'   => __( 'Beloningsbeleid', 'siw' ),
				'values' => $instance['renumeration_policy'],
			],
			[
				'name'   => __( 'Jaarverslagen', 'siw' ),
				'values' => $this->get_annual_reports(),
			],
		];

		return $parameters;
	}

	/**
	 * Geeft jaarverslagen terug
	 * 
	 * @return array
	 */
	protected function get_annual_reports() : array {
		$annual_reports = siw_get_option( 'annual_reports' );
		if ( empty( $annual_reports ) ) {
			return [];
		}
		$reports = [];
		foreach ( $annual_reports as $report ) {
			$url = wp_get_attachment_url( $report['file'][0] );
			$text = sprintf( esc_html__( 'Jaarverslag %s', 'siw' ), $report['year'] );
			$reports[ $report['year'] ] = Links::generate_document_link( $url, $text );
		}
		krsort( $reports );
		return array_values( $reports );
	}
}
