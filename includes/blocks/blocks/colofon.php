<?php declare(strict_types=1);

namespace SIW\Blocks\Blocks;

use SIW\Interfaces\Blocks\Block as Block_Interface;
use SIW\Core\Template;

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
	public function get_fields(): array {
		return [
			[
                'type' => 'text',
                'id'   => 'organisation',
                'name' => 'Organisation',
                'size' => 10,
            ],
            [
                'type' => 'textarea',
                'id'   => 'description',
                'before'  => 'Description',
                'cols' => 20,
            ],
            [
                'type' => 'color',
                'id'   => 'backgroundcolor',
                'before' => 'BackgroundColor',
            ],
            [
                'type' => 'single_image',
                'id'    => 'gebouw',
                'before'  => 'gebouw',
            ]
		];
    }
    /**
     * derfinieer moustache tamplate
     */
    public function get_template(): string {
        return('colofon');
    }
    public function xget_template_vars($attributes): array {
        $gebouw=mb_get_block_field( 'gebouw' );
        return [
        'organisation'  => mb_get_block_field( 'organisation' ),
        'backgroundcolor' => mb_get_block_field( 'backgroundcolor' ),
        'description' => mb_get_block_field( 'description' ),
        'gebouw' => $gebouw['full_url'],
        ];
    }
    public function get_template_vars($attributes): array {
        $data = $attributes["data"];
        $gebouw=$data['gebouw'];
        $gebouw=mb_get_block_field( 'gebouw' );
        return [
        'organisation'  => $data['organisation'],
        'backgroundcolor' => $data['backgroundcolor'],
        'description' => $data['description'],
        'gebouw' => $gebouw['full_url'],
        #'gebouw' => $gebouw,
        ];
    }
}
