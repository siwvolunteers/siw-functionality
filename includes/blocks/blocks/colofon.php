<?php declare(strict_types=1);

namespace SIW\Blocks\Blocks;

use SIW\Interfaces\Blocks\Block as Block_Interface;

/**
 * Demo MB block
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Colofon implements Block_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'colofonblock';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'ColofonBlock', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_description(): string {
		return 'beschrijving';
	}

		/** {@inheritDoc} */
	public function get_icon(): string {
		return 'bank';
	}

	/** {@inheritDoc} */
	public function get_fields(): array {
		return [
			[
				'type' => 'text',
				'id'   => 'organisation',
				'name' => 'Organisation',
				'size' => 10,
			],
			[
				'type'   => 'textarea',
				'id'     => 'description',
				'before' => 'Description',
				'cols'   => 20,
			],
			[
				'type'   => 'color',
				'id'     => 'backgroundcolor',
				'before' => 'BackgroundColor',
			],
			[
				'type'   => 'single_image',
				'id'     => 'gebouw',
				'before'  => 'gebouw',
			]
		];
	}

	/** {@inheritDoc} */
	public function get_template(): string {
		return 'colofon';
	}

	/** {@inheritDoc} */
	public function get_template_vars( array $attributes ): array {
		$data = $attributes['data'];
		$gebouw = $data['gebouw'];
		$gebouw = mb_get_block_field( 'gebouw' );
		return [
			'organisation'    => $data['organisation'] ?? '',
			'backgroundcolor' => $data['backgroundcolor'] ?? '',
			'description'     => $data['description'] ?? '',
			'gebouw'          => $gebouw['full_url'] ?? '',
		];
	}
}
