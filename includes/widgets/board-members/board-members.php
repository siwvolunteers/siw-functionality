<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Board_Title;

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
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
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
				'title'      => Board_Title::tryFrom( $board_member['title'] )?->label(),
			],
			$board_members
		);

		return [
			'board_members' => $board_members,
		];
	}

}
