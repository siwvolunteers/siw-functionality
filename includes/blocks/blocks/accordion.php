<?php declare(strict_types=1);

namespace SIW\Blocks\Blocks;

use SIW\Interfaces\Blocks\Block as Block_Interface;
use SIW\Elements\Accordion as Accordion_Element;
use SIW\Core\Template;

/**
 * Demo MB block
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
		return "default";
	}
	
	/** {@inheritDoc} */
	public function get_fields() : array{
		$fields = [
			'title' => [
				'id'	=> 'titel',
				'type'  => 'text',
				'name' => __( 'Titel accordion', 'siw' ),
			],
			'child_fields' => [
				'id'		=>	'paneel',
				'type'       => 'group',
				'clone'		=> TRUE,
				'add_button'	=>	'+ Paneel',
				'name'      => __( 'Accordeon' , 'siw' ),
				'fields' => [
					'title' => [
						'id'	=> 'title',
						'type'  => 'text',
						'name' => __( 'Titel', 'siw' )
					],
					'content' => [
						'id'			=> 'content',
						'type'           => 'textarea',
						'name'          => __( 'Inhoud', 'siw' ),
						'rows'           => 10,
						'default_editor' => 'html',
					],
					'show_button' => [
						'id'			=>	'show_button',
						'type'          => 'checkbox',
						'name'         => __( 'Toon een knop', 'siw' ),
						'default'       => false,
					],
					'button_text' => [
						'id'			=> 'button_text',
						'type'          => 'text',
						'name'         => __( 'Knoptekst', 'siw' ),
						'hidden' => array( 'show_button', '=', false )
					],
					'button_url' => [
						'id'			=> 'button_url',
						'type'          => 'text',
						'name'         => __( 'URL', 'siw' ),
						'description'   => __( 'Relatief', 'siw' ),
						'hidden' => array( 'show_button', '=', false )
					],
				]
			]
		];
		return $fields;
	}
   /**
     * derfinieer moustache tamplate
     */
	/** {@inheritDoc} */
	function get_template_vars( $attributes) : array{
		$data = $attributes["data"];
		$content = Accordion_Element::create()->add_items( $data['paneel'] )->generate();
		return [
			'content' => $content
		];
	}
}
