<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Board_Title;
use SIW\Elements\List_Columns;
use SIW\Elements\List_Style_Type;

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
		return Widget::DEFAULT_TEMPLATE_ID;
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
			fn( array $board_member ): string => sprintf(
				'%s %s<br/><i>%s</i>',
				$board_member['first_name'],
				$board_member['last_name'],
				Board_Title::tryFrom( $board_member['title'] )?->label(),
			),
			$board_members
		);

		return [
			'content' => List_Columns::create()
				->add_items( $board_members )
				->set_list_style_type( List_Style_Type::DISC )
				->generate(),
		];
	}
}
