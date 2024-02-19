<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Board_Title;
use SIW\Data\Elements\Unordered_List\List_Style_Type;
use SIW\Data\Icons\Dashicons;
use SIW\Elements\Unordered_List;
use SIW\Facades\Meta_Box;

/**
 * Widget Name: SIW: Bestuurssamenstellingddd
 * Description: Toont bestuurssamenstelling.!!
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Board_Members extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Bestuurssamenstelling', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont bestuurssamenstelling', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::BUSINESSMAN;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {

		$board_members = Meta_Box::get_option( 'board_members' );
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
			'content' => Unordered_List::create()
				->add_items( $board_members )
				->set_list_style_type( List_Style_Type::DISC )
				->generate(),
		];
	}
}
