<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Properties;

/**
 * Widget met bestuurssamenstelling
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Bestuurssamenstelling
 * Description: Toont bestuurssamenstelling.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Board_Members extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'board_members';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Bestuurssamenstelling', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont bestuurssamenstelling', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'businessman';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
				'default' => __( 'Bestuurssamenstelling', 'siw' ),
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

		$board_members = siw_get_option( 'board_members' );
		if ( empty( $board_members ) ) {
			return [];
		}

		$board_members = array_map(
			fn( array $board_member ): array => [
				'first_name' => $board_member['first_name'],
				'last_name'  => $board_member['last_name'],
				'title'      => siw_get_board_title( $board_member['title'] ),
			],
			$board_members
		);

		return [
			'board_members' => $board_members,
		];
	}

}
