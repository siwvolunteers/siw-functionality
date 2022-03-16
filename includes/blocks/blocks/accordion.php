<?php declare(strict_types=1);

namespace SIW\Blocks\Blocks;

use SIW\Interfaces\Blocks\Block as Block_Interface;
use SIW\Elements\Accordion as Accordion_Element;

/**
 * Accordion block
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Accordion implements Block_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'accordion';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Accordion', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_description(): string {
		return __( 'Toont accordion', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_template(): string {
		return 'default';
	}
	
	/** {@inheritDoc} */
	public function get_fields(): array{
		$fields = [
			[
				'id'   => 'title',
				'type' => 'text',
				'name' => __( 'Titel accordion', 'siw' ),
			],
			[
				'id'         => 'panes',
				'type'       => 'group',
				'clone'      => true,
				'add_button' => '+ Paneel',
				'name'       => __( 'Accordion' , 'siw' ),
				'fields'     => [
					[
						'id'   => 'title',
						'type' => 'text',
						'name' => __( 'Titel', 'siw' ),
					],
					[
						'id'   => 'content',
						'type' => 'wysiwyg',
						'name' => __( 'Inhoud', 'siw' ),
					],
					[
						'id'      => 'show_button',
						'type'    => 'checkbox',
						'name'    => __( 'Toon een knop', 'siw' ),
						'default' => false,
					],
					[
						'id'      => 'button_text',
						'type'    => 'text',
						'name'    => __( 'Knoptekst', 'siw' ),
						'visible' => [ 'show_button', '=', true ],
					],
					[
						'id'      => 'button_url',
						'type'    => 'text',
						'name'    => __( 'URL', 'siw' ),
						'desc'    => __( 'Relatief', 'siw' ),
						'visible' => [ 'show_button', '=', true ]
					],
				]
			]
		];
		return $fields;
	}

	/** {@inheritDoc} */
	function get_template_vars( array $attributes): array{
		
		return [
			'title'   => mb_get_block_field( 'title' ),
			'content' => Accordion_Element::create()->add_items( (array) mb_get_block_field( 'panes' ) )->generate()
		];
	}
}
