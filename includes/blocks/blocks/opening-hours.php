<?php declare(strict_types=1);

namespace SIW\Blocks\Blocks;

use SIW\Elements\List_Columns;
use SIW\Elements\Table;
use SIW\Interfaces\Blocks\Block as Block_Interface;

/**
 * Openingstijden
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Opening_Hours implements Block_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'opening-hours';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Openingstijden', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_icon(): string {
		return 'clock';
	}

	/** {@inheritDoc} */
	public function get_description(): string {
		return __( 'Toont openingstijden van SIW kantoor', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_fields(): array {
		return [
			[
				'id'      => 'type',
				'name'    => __( 'Type', 'siw' ),
				'type'    => 'radio',
				'options' => [ 
					'table' => __( 'Tabel', 'siw' ), 
					'list'  => __( 'Lijst', 'siw' ),
				],
				'std'    => 'table',
				'inline' => true,
			]
		];
	}

	/** {@inheritDoc} */
	public function get_template(): string {
		return Block_Interface::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	public function get_template_vars( array $attributes ): array {
		switch( mb_get_block_field( 'type' ) ) {
			case 'table':
				$content = Table::create()->add_items( siw_get_opening_hours() )->generate();
				break;
			case 'list':
				$data = array_map(
					fn( array $value ): string => implode( ': ', $value ),
					siw_get_opening_hours()
				);
				$content = List_Columns::create()->add_items( $data )->generate();
				break;
			default:
				$content = '';
		}

		return [
			'content' => $content,
		];
	}
	
}